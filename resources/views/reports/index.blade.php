<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Reports</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- ملف CSS مخصص -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/Q.png') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="bi bi-list"></i>
    </button>

    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="custom-container">
            <div class="search-navbar">
                <div class="search-bar">
                    <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2 w-100">
                        <input
                            type="text"
                            name="search"
                            class="form-control search-input"
                            placeholder="Search reports..."
                            value="{{ $search ?? '' }}">

                        <button class="btn btn-saas-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <div class="search-loading" id="searchLoading">
                        <div class="spinner"></div>
                    </div>

                    <!-- نتائج البحث -->
                    <div class="search-results" id="searchResults">
                        <div class="recent-searches">
                            <div class="recent-title">
                                <span>Recent Searches</span>
                                <span class="clear-recent" id="clearRecent">Clear All</span>
                            </div>
                            <div class="recent-tags" id="recentTags">
                                <!-- Recent searches added via JS -->
                            </div>
                        </div>


                        <div class="result-list" id="resultList"></div>

                    </div>
                </div>


                <div class="logo-container">
                    <img id="platformLogo" src="{{ asset('images/Q.png') }}" alt="Logo" class="platform-logo">
                </div>
            </div>
        <div class="users-table-container">
            <div class="my-4 table-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <h1 style="color: rgba(39, 58, 65, 1); font-size: 24px;">Reports</h1>
                </div>
            </div>



            <div class="table-container">
                <div class="programmes-table table-responsive">
                    <table class="table table-responsive table-borderless users-table">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Reporter</th>
                                <th>Reported User</th>
                                <th>Article</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($reports as $report)
                            <tr class="text-center">

                                <td>{{ $report->id }}</td>

                                <td>{{ $report->reporter->name ?? 'Unknown' }}</td>

                                <td>{{ $report->reported->name ?? 'Unknown' }}</td>

                                <td>
                                    @if($report->article)
                                    <a href="{{ route('articles.show', $report->article_id) }}" class="text-primary">
                                        {{ Str::limit($report->article->title, 40) }}
                                    </a>
                                    @else
                                    <span class="text-muted">Article deleted</span>
                                    @endif
                                </td>


                                <td>{{ $report->reason }}</td>

                                <td>
                                    @if($report->status == 'reviewing')
                                    <span class="badge bg-warning">Reviewing</span>
                                    @elseif($report->status == 'resolved')
                                    <span class="badge bg-success">Resolved</span>
                                    @elseif($report->status == 'blocked')
                                    <span class="badge bg-danger">Blocked</span>
                                    @endif
                                </td>

                                <td>{{ $report->created_at->format('Y-m-d') }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-saas-primary dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown">
                                            Manage Status
                                        </button>

                                        <ul class="dropdown-menu text-start">

                                            <!-- حل البلاغ -->
                                            <li>
                                                <button type="button"
                                                    class="dropdown-item text-success confirm-action"
                                                    data-form="resolve-form-{{ $report->id }}"
                                                    data-title="Resolve report?"
                                                    data-text="The report will be marked as resolved."
                                                    data-confirm="Yes, resolve it">
                                                    Resolve Report
                                                </button>

                                                <form id="resolve-form-{{ $report->id }}"
                                                    action="{{ route('reports.update', $report->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="resolved">
                                                </form>
                                            </li>

                                            <!-- حظر المقال -->
                                            <li>
                                                <button type="button"
                                                    class="dropdown-item text-danger confirm-action"
                                                    data-form="block-form-{{ $report->id }}"
                                                    data-title="Are you sure?"
                                                    data-text="The article will be permanently deleted!"
                                                    data-confirm="Yes, delete article">
                                                    Block Article
                                                </button>

                                                <form id="block-form-{{ $report->id }}"
                                                    action="{{ route('reports.update', $report->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="blocked">
                                                </form>
                                            </li>

                                            <div class="dropdown-divider"></div>

                                            <!-- Contact Writer -->
                                            @if($report->reported_id)
                                            <li>
                                                <a class="dropdown-item text-info" href="{{ route('reports.resolution', ['id' => $report->id]) }}">
                                                    <i class="bi bi-chat-dots me-2"></i> Contact Writer
                                                </a>
                                            </li>
                                            @endif

                                        </ul>

                                    </div>
                                </td>

                            </tr>

                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No reports found</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="pagination-info">
                            Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} results
                        </div>

                        <div>
                            {{ $reports->links('pagination::bootstrap-5') }}
                        </div>

                        <!-- Jump to Page -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Jump to:</span>
                            <form method="GET" class="d-flex gap-2" style="max-width: 150px;">
                                @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <input
                                    type="number"
                                    name="page"
                                    min="1"
                                    max="{{ $reports->lastPage() }}"
                                    value="{{ $reports->currentPage() }}"
                                    class="form-control form-control-sm"
                                    style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-outline-saas-primary">Go</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       </div>

    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/articles.js') }}"></script>

</body>

</html>