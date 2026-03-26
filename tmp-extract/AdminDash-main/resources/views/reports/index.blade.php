<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مراجعة البلاغات</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ملف CSS مخصص -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/Q.png') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    <!-- زر القائمة المتنقلة -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- السايدبار -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <!-- صورة افتراضية للشعار -->
                    <img src="{{ asset('images/Q.png') }}" alt="Logo" width="40" />
                </div>
                <div class="logo-text mx-2 fw-bold ">
                    Quick App
                </div>
            </div>
            <button class="toggle-btn" id="toggleBtn">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>


        <nav class="sidebar-nav">
            <!-- قسم Home -->
            <div class="nav-section">
                <span class="section-title">الصفحة الرئيسية</span>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}" class="nav-link">
                            <i class="bi bi-house-door nav-icon"></i>
                            <span class="nav-text">المستخدمين</span>
                        </a>
                        <!-- مراجعة المقالات -->
                        <a href="{{ route('articles.index') }}" class="nav-link">
                            <i class="bi bi-file-earmark-text nav-icon"></i>
                            <span class="nav-text">مراجعة المقالات</span>
                        </a>

                        <!-- الإبلاغات -->
                        <a href="{{ route('reports.index') }}" class="nav-link active">
                            <i class="bi bi-flag nav-icon"></i>
                            <span class="nav-text">الإبلاغات</span>
                        </a>

                        <!-- إرسال إشعار -->
                        <a href="{{ route('notifications.index') }}" class="nav-link">
                            <i class="bi bi-bell nav-icon"></i>
                            <span class="nav-text">إرسال إشعار</span>
                        </a>

                    </li>
                </ul>
            </div>


        </nav>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content" id="mainContent">
        <div class="custom-container">
            <div class="search-navbar">
                <div class="search-bar">
                    <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2 w-100">
                        <input
                            type="text"
                            name="search"
                            class="form-control search-input"
                            placeholder="ابحث عن الإبلاغات..."
                            value="{{ $search ?? '' }}">

                        <button class="btn btn-primary">
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
                                <span>عمليات البحث الحديثة</span>
                                <span class="clear-recent" id="clearRecent">مسح الكل</span>
                            </div>
                            <div class="recent-tags" id="recentTags">
                                <!-- سيتم إضافة عمليات البحث الحديثة هنا عبر JavaScript -->
                            </div>
                        </div>


                        <div class="result-list" id="resultList"></div>

                    </div>
                </div>


                <div class="logo-container">
                    <img id="platformLogo" src="{{ asset('images/Q.png') }}" alt="Logo" class="platform-logo">
                </div>
            </div>
        </div>


        <div class="users-table-container">
            <div class="my-4 table-header">
                <h1 style="color: rgba(39, 58, 65, 1); font-size: 24px;">
                    البلاغات
                </h1>
            </div>


            <div class="table-container">
                <div class="programmes-table table-responsive">
                    <table class="table table-responsive table-borderless bg-white shadow-sm users-table">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>المُبلِّغ</th>
                                <th>المستخدم المُبلّغ عنه</th>
                                <th>المقال</th>
                                <th>السبب</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإدارة</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($reports as $report)
                            <tr class="text-center">

                                <td>{{ $report->id }}</td>

                                <td>{{ $report->reporter->name ?? 'غير معروف' }}</td>

                                <td>{{ $report->reported->name ?? 'غير معروف' }}</td>

                                <td>
                                    @if($report->article)
                                    <a href="{{ route('reports.show', $report->article_id) }}" class="text-primary">
                                        {{ $report->article->title }}
                                    </a>
                                    @else
                                    <span class="text-muted">تم حذف المقال</span>
                                    @endif
                                </td>


                                <td>{{ $report->reason }}</td>

                                <td>
                                    @if($report->status == 'reviewing')
                                    <span class="badge bg-warning">قيد المراجعة</span>
                                    @elseif($report->status == 'resolved')
                                    <span class="badge bg-success">تم الحل</span>
                                    @elseif($report->status == 'blocked')
                                    <span class="badge bg-danger">محظور</span>
                                    @endif
                                </td>

                                <td>{{ $report->created_at->format('Y-m-d') }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown">
                                            إدارة الحالة
                                        </button>

                                        <ul class="dropdown-menu text-end">

                                            <!-- حل البلاغ -->
                                            <li>
                                                <button type="button"
                                                    class="dropdown-item text-success confirm-action"
                                                    data-form="resolve-form-{{ $report->id }}"
                                                    data-title="حل البلاغ؟"
                                                    data-text="سيتم اعتبار البلاغ محلول."
                                                    data-confirm="نعم، تم الحل">
                                                    حل البلاغ
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
                                                    data-title="هل أنت متأكد؟"
                                                    data-text="سيتم حذف المقال من قاعدة البيانات نهائياً!"
                                                    data-confirm="نعم، احذف المقال">
                                                    حظر المقال
                                                </button>

                                                <form id="block-form-{{ $report->id }}"
                                                    action="{{ route('reports.update', $report->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="blocked">
                                                </form>
                                            </li>

                                        </ul>

                                    </div>
                                </td>

                            </tr>

                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">لا يوجد بلاغات</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">

                    <small>
                        عرض
                        {{ $reports->firstItem() }}
                        إلى
                        {{ $reports->lastItem() }}
                        من
                        {{ $reports->total() }}
                        نتيجة
                    </small>

                    <div>
                        {{ $reports->links('pagination::bootstrap-5') }}
                    </div>

                    <!-- القفز لصفحة معينة -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">انتقل إلى:</span>
                        <form method="GET" class="d-flex gap-2" style="max-width: 150px;">
                            <input
                                type="number"
                                name="page"
                                min="1"
                                max="{{ $reports->lastPage() }}"
                                value="{{ $reports->currentPage() }}"
                                class="form-control form-control-sm"
                                style="width: 70px;">

                            <button type="submit" class="btn btn-sm btn-outline-primary">اذهب</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ملف JS مخصص -->
    <script src="{{ asset('js/articles.js') }}"></script>

</body>

</html>