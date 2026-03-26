<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المستخدمين</title>
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
                        <a href="{{ route('admin.users') }}" class="nav-link active">
                            <i class="bi bi-house-door nav-icon"></i>
                            <span class="nav-text">المستخدمين</span>
                        </a>
                        <!-- مراجعة المقالات -->
                        <a href="{{ route('articles.index') }}" class="nav-link">
                            <i class="bi bi-file-earmark-text nav-icon"></i>
                            <span class="nav-text">مراجعة المقالات</span>
                        </a>

                        <!-- الإبلاغات -->
                        <a href="{{ route('reports.index') }}" class="nav-link">
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
                    <form action="{{ route('admin.users') }}" method="GET" class="d-flex gap-2 w-100">
                        <input
                            type="text"
                            name="search"
                            class="form-control search-input"
                            placeholder="ابحث عن المستخدمين..."
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


                        <div class="result-list" id="resultList">
                            <!-- سيتم إضافة نتائج البحث هنا عبر JavaScript -->
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
                <h1 style="color: rgba(39, 58, 65, 1); font-size: 24px;">
                    المستخدمين
                </h1>
            </div>

            <div class="table-container">
                <div class="programmes-table table-responsive">
                    <table class="table table-responsive table-borderless bg-white shadow-sm users-table">
                        <thead class="text-center">
                            <tr>
                                <th>اسم المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>حالة المستخدم</th>
                                <th>الدور</th>
                                <th>تاريخ الإنشاء</th>
                                <th>
                                    إدارة الحالة
                                </th>
                                <th>
                                    تغيير الدور
                                </th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            @forelse($users as $user)
                            <tr class="text-center">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>

                                <td>
                                    @if($user->status == 'active')
                                    <span class="badge bg-success">نشط</span>
                                    @elseif($user->status == 'suspended')
                                    <span class="badge bg-warning">موقوف مؤقتًا</span>
                                    @elseif($user->status == 'banned')
                                    <span class="badge bg-danger">محظور</span>
                                    @endif
                                </td>

                                <td>{{ $user->role }}</td>

                                <td>{{ $user->created_at->format('Y-m-d') }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            إدارة الحالة
                                        </button>
                                        <ul class="dropdown-menu">
                                            <!-- Status Update -->
                                            <li class="dropdown-header text-end">تغيير الحالة</li>
                                            <li>
                                                <form class="dropdown-item update-status-form"
                                                    action="{{ route('admin.users.updateStatus', $user) }}"
                                                    method="POST"
                                                    data-user-id="{{ $user->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select form-select-sm status-select">
                                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>نشط</option>
                                                        <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>موقوف مؤقتًا</option>
                                                        <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>محظور</option>
                                                    </select>
                                                </form>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            تغيير الدور
                                        </button>

                                        <ul class="dropdown-menu text-end" style="min-width: 160px;">
                                            <li>
                                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="reader">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'reader' ? 'active' : '' }}">قارئ</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="writer">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'writer' ? 'active' : '' }}">كاتب</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="admin">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'admin' ? 'active' : '' }}">مسؤول</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="admin_assistant">
                                                    <button type="submit" class="dropdown-item {{ $user->role == 'admin_assistant' ? 'active' : '' }}">مساعد مسؤول</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">لا يوجد مستخدمين</td>
                            </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <!-- Pagination مع إدخال للقفز لصفحة معينة -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="pagination-info">
                            عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من {{ $users->total() }} نتيجة
                        </div>

                        <div>
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>

                        <!-- القفز لصفحة معينة -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">انتقل إلى:</span>
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
                                <button type="submit" class="btn btn-sm btn-outline-primary">اذهب</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ملف JS مخصص -->
    <script src="{{ asset('js/dashboard.js') }}"></script>

</body>

</html>