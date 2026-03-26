<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Book ERA</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- ملف CSS مخصص -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Book Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/330/330731.png" type="image/png">

    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="bi bi-list"></i>
    </button>

    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="custom-container">
            <div class="search-navbar">
                <div class="search-bar">
                    <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2 w-100">
                        <input
                            type="text"
                            name="search"
                            class="form-control search-input"
                            placeholder="Search for users..."
                            value="{{ $search ?? '' }}">

                        <button class="btn btn-saas-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <div class="search-loading" id="searchLoading">
                        <div class="spinner"></div>
                    </div>

                    <!-- Search Results -->
                    <div class="search-results" id="searchResults">
                        <div class="recent-searches">
                            <div class="recent-title">
                                <span>Recent Searches</span>
                                <span class="clear-recent" id="clearRecent">Clear All</span>
                            </div>
                            <div class="recent-tags" id="recentTags">
                                <!-- Recent searches will be added here via JS -->
                            </div>
                        </div>


                        <div class="result-list" id="resultList">
                            <!-- Search results will be added here via JS -->
                        </div>
                    </div>
                </div>


                <div class="logo-container">
                    <img id="platformLogo" src="{{ asset('images/Q.png') }}" alt="Logo" class="platform-logo">
                </div>
            </div>
        </div>


        <div class="users-table-container">
            <div class="my-4 table-header">
                <h1 style="color: rgba(39, 58, 65, 1); font-size: 24px;">Users</h1>
            </div>

            <div class="table-container">
                <div class="programmes-table table-responsive">
                    <table class="table table-responsive table-borderless users-table">
                        <thead class="text-center">
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User Status</th>
                                <th>Role</th>
                                <th>Creation Date</th>
                                <th>Manage Status</th>
                                <th>Change Role</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            @forelse($users as $user)
                            <tr class="text-center">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>

                                <td>
                                    @if($user->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                    @elseif($user->status == 'suspended')
                                    <span class="badge bg-warning">Suspended</span>
                                    @elseif($user->status == 'banned')
                                    <span class="badge bg-danger">Banned</span>
                                    @endif
                                </td>

                                <td>{{ $user->role }}</td>

                                <td>{{ $user->created_at->format('Y-m-d') }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-saas-primary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Manage Status</button>
                                        <ul class="dropdown-menu">
                                            <!-- Status Update -->
                                            <li class="dropdown-header text-start">Change Status</li>
                                            <li>
                                                <form class="dropdown-item update-status-form"
                                                    action="{{ route('dashboard.updateStatus', $user) }}"
                                                    method="POST"
                                                    data-user-id="{{ $user->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select form-select-sm status-select">
                                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                        <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>Banned</option>
                                                    </select>
                                                </form>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Change Role</button>

                                        <ul class="dropdown-menu text-start" style="min-width: 160px;">
                                            <li>
                                                <form action="{{ route('dashboard.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="reader">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'reader' ? 'active' : '' }}">Reader</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('dashboard.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="writer">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'writer' ? 'active' : '' }}">Writer</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('dashboard.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="admin">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'admin' ? 'active' : '' }}">Admin</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('dashboard.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="admin_assistant">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'admin_assistant' ? 'active' : '' }}">Admin Assistant</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No users found</td>
                            </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <!-- Pagination with jump to page input -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="pagination-info">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                        </div>

                        <div>
                            {{ $users->links('pagination::bootstrap-5') }}
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
                                    max="{{ $users->lastPage() }}"
                                    value="{{ $users->currentPage() }}"
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

    <!-- Custom JS -->
    <script src="{{ asset('js/dashboard.js') }}"></script>

</body>

</html>