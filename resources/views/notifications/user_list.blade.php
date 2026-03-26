<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications - Quick App</title>
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <style>
        .notification-item {
            background: rgba(13, 30, 54, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }
        .notification-item.unread {
            background: rgba(255, 214, 10, 0.03);
            border-left: 4px solid var(--accent-yellow);
        }
        .notification-item:hover {
            background: rgba(13, 30, 54, 0.5);
            transform: translateX(4px);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex align-items-center justify-content-between mb-5">
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-saas-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left me-2"></i> Back
                </a>
                <h1 class="text-white fw-bold mb-0">Notifications</h1>
            </div>
            
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button class="btn btn-outline-saas-primary btn-sm">Mark All as Read</button>
            </form>
            @endif
        </div>

        <div class="notification-list">
            @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->unread() ? 'unread' : '' }}">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="d-flex align-items-start gap-3">
                        <div class="stat-icon-box bg-accent-10 p-2 rounded-circle">
                            <i class="bi bi-bell text-accent fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-white fw-bold mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                            <p class="text-white-50 small mb-2">{{ $notification->data['body'] ?? '' }}</p>
                            <small class="text-white-50 x-small">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    
                    @if($notification->unread())
                    <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-link text-accent p-0 btn-sm text-decoration-none">Mark Read</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-bell-slash text-white-50 fs-1 opacity-25 d-block mb-3"></i>
                <p class="text-white-50">No notifications yet.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</body>
</html>
