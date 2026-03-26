<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الاشعارات</title>
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
                        <a href="{{ route('reports.index') }}" class="nav-link">
                            <i class="bi bi-flag nav-icon"></i>
                            <span class="nav-text">الإبلاغات</span>
                        </a>

                        <!-- إرسال إشعار -->
                        <a href="{{ route('notifications.index') }}" class="nav-link active">
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


        <div class="users-table-container">
            <div class="my-4 table-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <h1 style="color: rgba(39, 58, 65, 1); font-size: 24px;">
                        إدارة الاشعارات
                    </h1>

                    <button class="btn btn-success mb-3"
                        data-bs-toggle="modal"
                        data-bs-target="#sendGlobalNotificationModal">
                        إرسال إشعار عام
                    </button>

                    <!-- Modal إرسال إشعار عام -->
                    <div class="modal fade" id="sendGlobalNotificationModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">إرسال إشعار عام</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('notifications.store') }}" method="POST">
                                    @csrf

                                    <div class="modal-body">

                                        <!-- نوع الإرسال -->
                                        <div class="mb-3">
                                            <label class="form-label">إرسال إلى</label>
                                            <select name="target" class="form-control" required>
                                                <option value="all">جميع المستخدمين</option>
                                                <option value="authors">الكتّاب فقط</option>
                                                <option value="readers">القرّاء فقط</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">عنوان الإشعار</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">نص الإشعار</label>
                                            <textarea name="body" class="form-control" rows="4" required></textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-success">إرسال الإشعار</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                </div>
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
                                <th>إرسال إشعار</th>
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
                                    <button class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#sendNotificationModal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}">
                                        إرسال إشعار
                                    </button>
                                </td>

                                <!-- Modal إرسال إشعار -->
                                <div class="modal fade" id="sendNotificationModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">إرسال إشعار للمستخدم</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>

                                            <form action="{{ route('notifications.store') }}" method="POST">
                                                @csrf

                                                <div class="modal-body">

                                                    <input type="hidden" name="target" value="user">
                                                    <input type="hidden" id="notifUserId" name="meta[user_id]">

                                                    <div class="mb-3">
                                                        <label class="form-label">المستخدم</label>
                                                        <input type="text" id="notifUserName" class="form-control" disabled>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">عنوان الإشعار</label>
                                                        <input type="text" name="title" class="form-control" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">نص الإشعار</label>
                                                        <textarea name="body" class="form-control" rows="4" required></textarea>
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-primary">إرسال الإشعار</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>


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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var notifModal = document.getElementById('sendNotificationModal');

            notifModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                var userId = button.getAttribute('data-user-id');
                var userName = button.getAttribute('data-user-name');

                document.getElementById('notifUserId').value = userId;
                document.getElementById('notifUserName').value = userName;
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ملف JS مخصص -->
    <script src="{{ asset('js/dashboard.js') }}"></script>

</body>

</html>