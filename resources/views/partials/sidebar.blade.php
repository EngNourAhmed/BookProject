    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon text-accent">
                    <i class="bi bi-book-half fs-2"></i>
                </div>
                <div class="logo-text mx-2 fw-bold text-white">Book ERA</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @if(auth()->user()->role == 'admin')
            <!-- Admin Menu -->
            <div class="nav-section">
                <span class="section-title">Admin Management</span>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard*') || Request::is('admin/users*') ? 'active' : '' }}">
                            <i class="bi bi-house-door nav-icon"></i>
                            <span class="nav-text">Users</span>
                        </a>
                        <a href="{{ route('articles.index') }}" class="nav-link {{ Request::is('articles*') && !Request::is('articles/create') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text nav-icon"></i>
                            <span class="nav-text">Review Articles</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="nav-link {{ Request::is('reports') ? 'active' : '' }}">
                            <i class="bi bi-flag nav-icon"></i>
                            <span class="nav-text">Reports</span>
                        </a>
                        <a href="{{ route('reports.resolution') }}" class="nav-link {{ Request::routeIs('reports.resolution') ? 'active' : '' }}">
                            <i class="bi bi-chat-left-text nav-icon"></i>
                            <span class="nav-text">Report Resolution</span>
                        </a>
                        <a href="{{ route('notifications.index') }}" class="nav-link {{ Request::is('notifications*') && !Request::is('notifications/user*') ? 'active' : '' }}">
                            <i class="bi bi-bell nav-icon"></i>
                            <span class="nav-text">Send Notification</span>
                        </a>
                        <a href="{{ route('messages.index') }}" class="nav-link {{ Request::routeIs('messages.index') && !request()->has('support') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots nav-icon"></i>
                            <span class="nav-text">Support Messages</span>
                        </a>
                    </li>
                </ul>
            </div>
            @else
            <!-- Writer/Reader Menu -->
            <div class="nav-section">
                <span class="section-title">{{ auth()->user()->role == 'writer' ? 'Writer Menu' : 'Reader Menu' }}</span>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ auth()->user()->role == 'writer' ? route('writer.dashboard') : route('reader.dashboard') }}" class="nav-link {{ Request::routeIs('writer.dashboard') || Request::routeIs('reader.dashboard') ? 'active' : '' }}">
                            <i class="bi {{ auth()->user()->role == 'writer' ? 'bi-house-door' : 'bi-grid' }} nav-icon"></i>
                            <span class="nav-text">{{ auth()->user()->role == 'writer' ? 'My Dashboard' : 'Browse Articles' }}</span>
                        </a>
                    </li>
                    @if(auth()->user()->role == 'writer')
                    <li class="nav-item">
                        <a href="{{ route('writer.profile', auth()->id()) }}" class="nav-link {{ Request::routeIs('writer.profile') ? 'active' : '' }}">
                            <i class="bi bi-person-badge nav-icon"></i>
                            <span class="nav-text">My Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('articles.myArticles') }}" class="nav-link {{ Request::routeIs('articles.myArticles') ? 'active' : '' }}">
                            <i class="bi bi-book nav-icon"></i>
                            <span class="nav-text">My Articles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('writer.drafts') }}" class="nav-link {{ Request::routeIs('writer.drafts') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text nav-icon"></i>
                            <span class="nav-text">My Drafts</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('notifications.user_index') }}" class="nav-link {{ Request::routeIs('notifications.user_index') ? 'active' : '' }}">
                            <i class="bi bi-bell nav-icon"></i>
                            <span class="nav-text">Notifications</span>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-pill ms-auto x-small" style="font-size: 0.6rem;">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('messages.index') }}" class="nav-link {{ Request::routeIs('messages.index') && !request()->has('support') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots nav-icon"></i>
                            <span class="nav-text">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('messages.index', ['support' => 'true', 'user_id' => 11]) }}" class="nav-link {{ request()->has('support') ? 'active' : '' }}">
                            <i class="bi bi-headset nav-icon"></i>
                            <span class="nav-text">Support</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif
            
        <div class="sidebar-footer mt-auto">
            <ul class="nav-menu">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">@csrf</form>
                    <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right nav-icon"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
