<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writer Dashboard - Book ERA</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- ملف CSS مخصص -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/330/330731.png" type="image/png">
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
        <div class="header-banner mb-4">
            <div class="d-flex align-items-center justify-content-between p-4 rounded-4 border border-white-5 border-opacity-10 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(255, 214, 10, 0.15) 0%, rgba(13, 30, 54, 0.8) 100%);">
                <!-- Decorative element -->
                <div class="position-absolute top-0 end-0 p-5 opacity-10" style="transform: translate(20%, -30%) rotate(15deg);">
                    <i class="bi bi-hexagon-fill text-accent" style="font-size: 15rem;"></i>
                </div>
                <div class="position-relative z-1">
                    <h2 class="text-white fw-bold mb-1">Writer Dashboard</h2>
                    <p class="text-white-50 small mb-0">Welcome back, <span class="text-accent fw-bold">{{ auth()->user()->name }}</span>! Here's your performance overview.</p>
                </div>
                <div class="d-flex align-items-center gap-4 position-relative z-1">
                    <a href="{{ route('notifications.user_index') }}" class="position-relative d-inline-flex align-items-center justify-content-center hover-scale transition-all" style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--accent-yellow) !important; box-shadow: 0 4px 15px rgba(255, 214, 10, 0.4);">
                        <i class="bi bi-bell-fill fs-5" style="color: var(--primary-navy) !important;"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm border border-navy" style="font-size: 0.6rem; transform: translate(-30%, -30%) !important;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <div class="users-table-container">
            <!-- Stats Row -->
            <div class="row g-4 mb-5">
                <div class="col-xl-3 col-md-6">
                    <div class="premium-card p-4 h-100 transition-all position-relative" style="background: linear-gradient(180deg, rgba(255,255,255,0.05) 0%, rgba(0,0,0,0) 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon-box bg-accent-10 me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 1px solid rgba(255,214,10,0.2);">
                                <i class="bi bi-file-earmark-text text-accent fs-4"></i>
                            </div>
                            <h6 class="text-white-50 mb-0">Total Articles</h6>
                        </div>
                        <h2 class="display-6 fw-bold text-white mb-0">{{ $articles->count() }}</h2>
                        <div class="mt-2 small text-accent fw-medium">
                            <i class="bi bi-arrow-up-circle-fill me-1"></i> Growing library
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="premium-card p-4 h-100 transition-all position-relative" style="background: linear-gradient(180deg, rgba(25,135,84,0.05) 0%, rgba(0,0,0,0) 100%); border-top: 2px solid rgba(25,135,84,0.3);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon-box bg-success-10 me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 1px solid rgba(25,135,84,0.2);">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </div>
                            <h6 class="text-white-50 mb-0">Approved</h6>
                        </div>
                        <h2 class="display-6 fw-bold text-white mb-0">{{ $articles->where('status', 'active')->count() }}</h2>
                        <div class="mt-2 small text-success fw-medium">
                            <i class="bi bi-shield-check me-1"></i> Quality content
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="premium-card p-4 h-100 transition-all position-relative" style="background: linear-gradient(180deg, rgba(13,202,240,0.05) 0%, rgba(0,0,0,0) 100%); border-top: 2px solid rgba(13,202,240,0.3);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon-box bg-info-10 me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 1px solid rgba(13,202,240,0.2);">
                                <i class="bi bi-people-fill text-info fs-4"></i>
                            </div>
                            <h6 class="text-white-50 mb-0">Followers</h6>
                        </div>
                        <h2 class="display-6 fw-bold text-white mb-0">{{ $followersCount }}</h2>
                        <div class="mt-2 small text-info fw-medium">
                            <i class="bi bi-graph-up-arrow me-1"></i> Community reach
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="premium-card p-4 h-100 transition-all position-relative" style="background: linear-gradient(180deg, rgba(255,193,7,0.05) 0%, rgba(0,0,0,0) 100%); border-top: 2px solid rgba(255,193,7,0.3);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon-box bg-warning-10 me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 1px solid rgba(255,193,7,0.2);">
                                <i class="bi bi-heart-fill text-warning fs-4"></i>
                            </div>
                            <h6 class="text-white-50 mb-0">Total Likes</h6>
                        </div>
                        <h2 class="display-6 fw-bold text-white mb-0">{{ auth()->user()->articles->sum(fn($a) => $a->likes->count()) }}</h2>
                        <div class="mt-2 small text-warning fw-medium">
                            <i class="bi bi-activity me-1"></i> High engagement
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <!-- Top Articles -->
                <div class="col-lg-7">
                    <div class="bg-glass p-4 rounded-4 border border-white-5 border-opacity-10 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h3 class="fs-5 text-white fw-bold mb-0">Top Performing Articles</h3>
                            <i class="bi bi-trophy text-accent fs-4"></i>
                        </div>
                        @forelse($topArticles as $article)
                        <div class="d-flex align-items-center p-3 mb-3 bg-white-5 rounded-4 border border-white-5 border-opacity-5 hover-scale transition-all" style="box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <div class="me-3">
                                <div class="avatar-sm rounded-circle bg-accent text-dark d-flex align-items-center justify-content-center fw-bold shadow-sm" style="box-shadow: 0 0 10px rgba(255,214,10,0.4) !important;">
                                    {{ $loop->iteration }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-white mb-1 text-truncate" style="max-width: 250px;">{{ $article->title }}</h6>
                                <span class="text-white-50 x-small">{{ $article->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="text-end">
                                <div class="text-accent fw-bold">{{ $article->likes_count }} <i class="bi bi-heart-fill small"></i></div>
                                <div class="text-white-50 x-small">{{ $article->comments->count() }} comments</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <p class="text-white-50 mb-0">No articles yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Comments -->
                <div class="col-lg-5">
                    <div class="bg-glass p-4 rounded-4 border border-white-5 border-opacity-10 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h3 class="fs-5 text-white fw-bold mb-0">Recent Comments</h3>
                            <i class="bi bi-chat-left-text text-info fs-4"></i>
                        </div>
                        <div class="comment-scroll" style="max-height: 400px; overflow-y: auto;">
                            @forelse($recentComments as $comment)
                            <div class="mb-4 border-bottom border-white-5 border-opacity-5 pb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar-xs rounded-circle bg-info me-2 d-flex align-items-center justify-content-center text-white x-small">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-white x-small mb-0">{{ $comment->user->name }}</h6>
                                        <span class="text-white-50 xx-small">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <p class="text-white-50 small mb-1 line-clamp-2">"{{ $comment->content }}"</p>
                                <a href="{{ route('articles.show', $comment->article_id) }}" class="text-accent xx-small text-decoration-none">
                                    on {{ $comment->article->title }}
                                </a>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <p class="text-white-50 mb-0">No comments yet.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Articles Table -->
            <div class="table-header mb-4 mt-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fs-4 text-white fw-bold mb-1">Manage Articles</h2>
                        <p class="text-white-50 small mb-0">Tracking your literature performance across the platform.</p>
                    </div>
                    <button class="btn btn-saas-primary px-4 py-2 hover-scale d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createArticleModal">
                        <i class="bi bi-plus-lg fs-5"></i> <span class="fw-bold">New Article</span>
                    </button>
                </div>
            </div>

            <div class="table-container bg-glass rounded-4 border border-white-5 border-opacity-10 overflow-hidden">
                <div class="table-responsive">
                    <table class="table users-table table-borderless table-hover mb-0">
                        <thead class="bg-white-5">
                            <tr class="text-white-50 x-small text-uppercase ls-1">
                                <th class="ps-4 py-3">Title & Information</th>
                                <th class="text-center py-3">Status</th>
                                <th class="text-center py-3">Engagement</th>
                                <th class="text-center py-3">Created</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                            <tr class="align-middle border-bottom border-white-5 border-opacity-5">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="article-icon bg-white-5 rounded-3 p-2 me-3">
                                            <i class="bi bi-journal-text text-accent"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-white mb-0">{{ $article->title }}</div>
                                            <div class="text-white-50 x-small">{{ Str::limit(strip_tags($article->content), 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($article->status == 'active')
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill x-small border border-success border-opacity-25">Published</span>
                                    @elseif($article->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill x-small border border-warning border-opacity-25">Pending Review</span>
                                    @elseif($article->status == 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill x-small border border-secondary border-opacity-25">Draft</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill x-small border border-danger border-opacity-25">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <span class="text-white-50 x-small" title="Likes"><i class="bi bi-heart-fill text-accent me-1 small"></i> {{ $article->likes->count() }}</span>
                                        <span class="text-white-50 x-small" title="Comments"><i class="bi bi-chat-fill text-info me-1 small"></i> {{ $article->comments->count() }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-white small">{{ $article->created_at->format('M d, Y') }}</div>
                                    <div class="text-white-50 xx-small">{{ $article->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-glass text-white border-white-10">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-sm btn-glass text-white border-white-10" data-bs-toggle="dropdown" aria-expanded="false" title="Share Article">
                                                <i class="bi bi-share"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->id)) }}" target="_blank"><i class="bi bi-facebook text-primary"></i> Facebook</a></li>
                                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://twitter.com/intent/tweet?url={{ urlencode(route('articles.show', $article->id)) }}&text={{ urlencode($article->title) }}" target="_blank"><i class="bi bi-twitter text-info"></i> Twitter</a></li>
                                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('articles.show', $article->id)) }}&title={{ urlencode($article->title) }}" target="_blank"><i class="bi bi-linkedin text-primary"></i> LinkedIn</a></li>
                                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . route('articles.show', $article->id)) }}" target="_blank"><i class="bi bi-whatsapp text-success"></i> WhatsApp</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item d-flex align-items-center gap-2 share-article-btn" data-url="{{ route('articles.show', $article->id) }}"><i class="bi bi-link-45deg"></i> Copy Link</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-white-50">
                                    <div class="py-4">
                                        <i class="bi bi-journal-plus fs-1 d-block mb-3 opacity-25"></i>
                                        <p class="mb-4">You haven't written any articles yet.</p>
                                        <button class="btn btn-saas-primary btn-sm px-4" data-bs-toggle="modal" data-bs-target="#createArticleModal">
                                            Write Your First Article
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal_create_article')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    
    <script>
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                background: '#0d1e36',
                color: '#fff',
                iconColor: '#ffd60a'
            });
        @endif

        // Share button logic
        document.addEventListener('click', function(e) {
            const shareBtn = e.target.closest('.share-article-btn');
            if (shareBtn) {
                const url = shareBtn.getAttribute('data-url');
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: 'Link Copied!',
                        text: 'Article link has been copied to your clipboard.',
                        background: '#0d1e36',
                        color: '#fff',
                        iconColor: '#ffd60a'
                    });
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            }
        });
    </script>
</body>
</html>
