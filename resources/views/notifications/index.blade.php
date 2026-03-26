<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
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
                    <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2 w-100">
                        <input
                            type="text"
                            name="search"
                            class="form-control search-input"
                            placeholder="Search users for notifications..."
                            value="{{ $search ?? '' }}">

                        <button class="btn btn-saas-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
                <div class="logo-container">
                    <img id="platformLogo" src="{{ asset('images/Q.png') }}" alt="Logo" class="platform-logo">
                </div>
            </div>
        </div>

        <div class="users-table-container">
            <div class="my-4 table-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <h1 style="color: rgba(39, 58, 65, 1); font-size: 24px;">
                        Notifications Management
                    </h1>

                    <button class="btn btn-saas-primary mb-3"
                        data-bs-toggle="modal"
                        data-bs-target="#sendGlobalNotificationModal">
                        Send Global Notification
                    </button>


                </div>
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
                                <th>Created At</th>
                                <th>Send Notification</th>
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
                                    <button class="btn btn-sm btn-saas-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#sendNotificationModal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}">
                                        Send Notification
                                    </button>
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
                <!-- Pagination مع إدخال للقفز لصفحة معينة -->
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
    </div>

    <!-- Modal إرسال إشعار عام -->
    <div class="modal fade" id="sendGlobalNotificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-broadcast me-2"></i> Send Global Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('notifications.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Send To -->
                        <div class="mb-4">
                            <label class="form-label">Target Audience</label>
                            <select name="target" class="form-select" required>
                                <option value="all">Everyone</option>
                                <option value="authors">Authors Only</option>
                                <option value="readers">Readers Only</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Notification Title</label>
                            <input type="text" name="title" class="form-control" placeholder="What's this about?" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Notification Message</label>
                            <textarea name="body" class="form-control" rows="5" placeholder="Enter your message here..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-saas-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-saas-primary px-4">Broadcast Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal إرسال إشعار فردي -->
    <div class="modal fade" id="sendNotificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-send me-2"></i> Direct Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('notifications.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="target" value="user">
                        <input type="hidden" id="notifUserId" name="meta[user_id]">
                        <div class="mb-4">
                            <label class="form-label">Recipient</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" id="notifUserName" class="form-control border-start-0 ps-0" disabled>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Quick topic..." required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Message Details</label>
                            <textarea name="body" class="form-control" rows="4" placeholder="Type your message here..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-saas-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-saas-primary px-4">Send Now</button>
                    </div>
                </form>
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

    <!-- Custom JS -->
    <script src="{{ asset('js/dashboard.js') }}"></script>

</body>

</html>