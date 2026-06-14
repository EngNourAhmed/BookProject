<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - Book ERA</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <!-- Google Fonts for Books Vibes -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #0a1120;
            color: var(--text-white);
            min-height: 100vh;
        }
        .reading-room {
            background: rgba(13, 30, 54, 0.4); /* Transparent glass */
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            color: #ffffff;
            border-radius: var(--radius-xl);
            padding: 4.5rem 5.5rem;
            margin-top: 1rem;
            box-shadow: 
                0 40px 100px rgba(0,0,0,0.6), 
                0 0 0 1px rgba(255,255,255,0.08);
            position: relative;
            overflow: hidden;
            border-left: 10px solid var(--accent-yellow);
        }

        .reading-room::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("https://www.transparenttextures.com/patterns/carbon-fibre.png");
            opacity: 0.05;
            pointer-events: none;
        }

        .article-title {
            font-family: 'Playfair Display', serif;
            color: var(--accent-yellow) !important;
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.1;
            margin-bottom: 2.5rem !important;
            border-bottom: 1px solid rgba(255, 214, 10, 0.15);
            padding-bottom: 1.5rem;
        }

        .article-meta-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }

        .article-meta-box h6, .article-meta-box span, .article-meta-box small {
            color: #ffffff !important;
        }

        .article-content {
            font-family: 'Playfair Display', serif;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.9;
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Override some dark theme defaults for the reading room */
        .reading-room .badge {
            background: #14233c !important;
            color: #ffd60a !important;
        }

        .reading-room .text-white-50, .reading-room .text-white {
            color: #4a5568 !important;
        }

        .reading-room .border-white-5 {
            border-color: rgba(20, 35, 60, 0.1) !important;
        }
        
        .action-bar-light {
            background: rgba(4, 13, 26, 0.4);
            border-top: 1px solid rgba(255, 214, 10, 0.2);
            border-bottom: 1px solid rgba(255, 214, 10, 0.2);
            padding: 1.5rem !important;
            backdrop-filter: blur(10px);
        }

        .btn-literary {
            background: #040d1a;
            color: var(--accent-yellow) !important;
            border: none;
            padding: 10px 25px;
            border-radius: 2px;
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-literary:hover {
            background: var(--accent-yellow);
            color: #040d1a !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .badge-literary {
            background: #040d1a;
            color: var(--accent-yellow);
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            letter-spacing: 0.1em;
        }

        @media (max-width: 768px) {
            .reading-room {
                padding: 2rem 1.5rem !important;
                border-left-width: 5px !important;
            }
            .article-title {
                font-size: 2rem !important;
                margin-bottom: 1.5rem !important;
                padding-bottom: 1rem !important;
            }
            .article-content {
                font-size: 1.1rem !important;
                line-height: 1.7 !important;
            }
            .action-bar-light {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 1rem !important;
            }
            .action-bar-light > div {
                flex-wrap: wrap !important;
                justify-content: space-between !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-4">
            @php
                $backRoute = route('articles.index');
                if(auth()->user()->role === 'writer') $backRoute = route('writer.dashboard');
                if(auth()->user()->role === 'reader') $backRoute = route('reader.dashboard');
            @endphp
            <a href="{{ $backRoute }}" class="btn btn-saas-secondary">
                <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>

        <div class="reading-room">
            <header class="mb-5 pb-4 border-bottom border-white-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-accent-10 text-accent px-3 py-1 rounded-pill x-small text-uppercase">Article</span>
                    <span class="text-white-50 x-small">• {{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }}</span>
                </div>
                <h1 class="article-title text-dark mb-4 fw-bold" style="font-size: 3rem; font-family: 'Playfair Display', serif;">{{ $article->title }}</h1>
                
                <div class="article-meta-box d-flex align-items-center justify-content-between px-4 py-3 rounded-2 mb-4">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('writer.profile', $article->user_id) }}" class="text-decoration-none d-flex align-items-center hover-accent">
                            <div class="avatar-sm me-3" style="width: 48px; height: 48px; background: rgba(255, 214, 10, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--accent-yellow);">
                                <i class="bi bi-person fs-4 text-accent"></i>
                            </div>
                             <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0 fw-bold text-white">{{ $article->user->name }}</h6>
                                    @if(auth()->id() !== $article->user_id)
                                        <button class="btn btn-sm {{ auth()->user()->isFollowing($article->user_id) ? 'btn-accent text-navy' : 'btn-outline-accent text-white' }} fw-bold rounded-pill px-3 py-0 follow-btn shadow-sm" data-id="{{ $article->user_id }}" style="font-size: 0.75rem; border-width: 1px;">
                                            {{ auth()->user()->isFollowing($article->user_id) ? 'Following' : 'Follow' }}
                                        </button>
                                    @endif
                                </div>
                                <small class="text-white-50">Verified Author</small>
                            </div>
                        </a>
                    </div>
                </div>
            </header>

            <div class="article-content mb-5 mt-4" style="min-height: 200px; text-align: start;" dir="auto">
                {!! nl2br(e($article->content)) !!}
            </div>

            <!-- Action Bar -->
            <div class="action-bar d-flex align-items-center justify-content-between p-3 rounded-4 action-bar-light mb-5">
                <div class="d-flex align-items-center gap-3">
                    <button id="likeBtn" class="btn btn-literary d-flex align-items-center gap-2" data-id="{{ $article->id }}">
                        <i class="bi {{ $article->likes()->where('user_id', auth()->id())->exists() ? 'bi-heart-fill' : 'bi-heart' }} fs-5"></i>
                        <span id="likesCount">{{ $article->likes->count() }}</span>
                        <span class="small d-none d-sm-inline">Appreciation</span>
                    </button>
                    
                    <div class="dropdown">
                        <button type="button" class="btn btn-literary d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" title="Share this article">
                            <i class="bi bi-share-fill fs-5"></i>
                            <span class="small d-none d-sm-inline">Share</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->id)) }}" target="_blank"><i class="bi bi-facebook text-primary"></i> Facebook</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://twitter.com/intent/tweet?url={{ urlencode(route('articles.show', $article->id)) }}&text={{ urlencode($article->title) }}" target="_blank"><i class="bi bi-twitter text-info"></i> Twitter</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('articles.show', $article->id)) }}&title={{ urlencode($article->title) }}" target="_blank"><i class="bi bi-linkedin text-primary"></i> LinkedIn</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . route('articles.show', $article->id)) }}" target="_blank"><i class="bi bi-whatsapp text-success"></i> WhatsApp</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item d-flex align-items-center gap-2 share-article-btn" data-url="{{ route('articles.show', $article->id) }}"><i class="bi bi-link-45deg"></i> Copy Link</button></li>
                        </ul>
                    </div>
                    
                    <button type="button" id="toggleCommentsBtn" class="btn btn-link text-white text-decoration-none small fw-extrabold" style="letter-spacing: 0.05em; font-family: 'Inter', sans-serif;">
                        <i class="bi bi-chat-left-dots-fill me-1 text-accent"></i> <span id="commentsCountText" class="text-white">{{ strtoupper($article->comments->count()) }} DISCUSSIONS</span>
                        <i class="bi bi-chevron-down ms-1 small text-white-50"></i>
                    </button>
                    
                    <button type="button" class="btn btn-link text-danger text-decoration-none small fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="bi bi-flag-fill me-1"></i> REPORT CONTENT
                    </button>
                </div>

                <!-- Remove Status section as requested -->
            </div>
            
            @if($article->status == 'pending' && auth()->user()->role === 'admin')
            <div class="admin-actions d-flex gap-3 mb-5 p-4 rounded-4 bg-danger-10 border border-danger-20">
                <div class="me-auto">
                    <h6 class="text-white fw-bold mb-1">Administrative Actions</h6>
                    <p class="text-white-50 small mb-0">This article is waiting for your approval before it goes public.</p>
                </div>
                <form action="{{ route('articles.approve', $article->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success px-4 rounded-pill">Approve</button>
                </form>
                <form action="{{ route('articles.reject', $article->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-outline-danger px-4 rounded-pill">Reject</button>
                </form>
            </div>
            @endif

            <!-- Comments Section (Hidden by Default) -->
            <div id="commentsSection" class="comments-section mx-auto pt-4" style="max-width: 850px; display: none;">
                <div class="p-4 rounded-4 bg-dark-5 border border-dark-10 mb-5">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-white border-opacity-10">
                    <h4 class="text-white fw-bold mb-0">
                        Reader Responses
                    </h4>
                    <span class="badge bg-accent text-dark px-3 rounded-pill">{{ $article->comments->count() }}</span>
                </div>
                
                <!-- Improved Comment Input Area (Midnight) -->
                <div class="d-flex gap-3 mb-5 p-3 rounded-4 bg-white-5 border border-white-10">
                    <div class="avatar-sm flex-shrink-0" style="width: 40px; height: 40px; background: rgba(255, 214, 10, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--accent-yellow);">
                        <i class="bi bi-person text-accent"></i>
                    </div>
                    <form id="commentForm" data-article-id="{{ $article->id }}" class="flex-grow-1">
                        @csrf
                        <div class="position-relative">
                            <input name="content" class="form-control bg-transparent text-white border-0 ps-0 submit-on-enter" placeholder="Share your insights..." id="commentText" style="height: 40px; border-bottom: 2px solid rgba(255, 214, 10, 0.2) !important; border-radius: 0;">
                            <button type="submit" class="btn btn-link text-accent position-absolute end-0 top-0 h-100 px-2 py-0">
                                <i class="bi bi-send-fill fs-5"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div id="commentsList" class="comments-list d-flex flex-column gap-4">
                    @forelse($article->comments->where('parent_id', null) as $comment)
                        @include('partials.comment', ['comment' => $comment])
                    @empty
                        <div id="noCommentsMessage" class="text-center py-5">
                            <div class="mb-3 opacity-20">
                                <i class="bi bi-chat-quote display-4 text-white"></i>
                            </div>
                            <h5 class="text-white-50">No voices here yet</h5>
                            <p class="text-white-50 small">Start the conversation by sharing your thoughts.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-navy border-bottom border-white-5">
                    <h5 class="modal-title text-white fw-bold"><i class="bi bi-flag text-danger me-2"></i> Report Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('reports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="article" value="{{ $article->id }}">
                    <div class="modal-body bg-navy p-4">
                        <div class="mb-3">
                            <label class="form-label text-white-50">Reason for reporting</label>
                            <select name="reason" id="reportReasonSelect" class="form-select bg-white-5 border-white-10 text-white" required>
                                <option value="" selected disabled>Select a reason...</option>
                                <option value="Inappropriate content">Inappropriate content</option>
                                <option value="Plagiarism">Plagiarism</option>
                                <option value="Harassment">Harassment</option>
                                <option value="Spam">Spam</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="otherReasonWrapper">
                            <label class="form-label text-white-50">Please specify</label>
                            <input type="text" name="description" id="reasonOtherInput" class="form-control bg-white-5 border-white-10 text-white" placeholder="Describe the issue...">
                        </div>
                        <div class="mb-0">
                            <p class="small text-white-50">Your report will be reviewed by the Book ERA administration team. Thank you for helping keep our community safe.</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-navy border-top border-white-5">
                        <button type="button" class="btn btn-saas-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Enhanced Comments Toggle Logic
        document.getElementById('toggleCommentsBtn').addEventListener('click', function() {
            const section = document.getElementById('commentsSection');
            const icon = this.querySelector('.bi-chevron-down, .bi-chevron-up');
            const isHidden = window.getComputedStyle(section).display === 'none';
            
            if (isHidden) {
            section.style.display = 'block';
            if(icon) icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
        } else {
            section.style.display = 'none';
            if(icon) icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
        }
    });

    // Auto-open comments if we have a hash (after reload)
    if(window.location.hash === '#commentsSection') {
        const section = document.getElementById('commentsSection');
        const btn = document.getElementById('toggleCommentsBtn');
        const icon = btn.querySelector('.bi-chevron-down');
        section.style.display = 'block';
        if(icon) icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
        section.scrollIntoView({ behavior: 'smooth' });
    }

        // AJAX Comment Submission
        const commentForm = document.getElementById('commentForm');
        if(commentForm) {
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const contentInput = document.getElementById('commentText');
                const content = contentInput.value;
                const articleId = this.dataset.articleId;
                
                if(!content.trim()) return;

                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                fetch(`/articles/${articleId}/comment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        contentInput.value = '';
                        submitBtn.disabled = false;
                        // For now we reload but with a hash to keep it open
                        window.location.hash = 'commentsSection';
                        location.reload();
                    }
                });
            });
        }

        // Event Delegation for Social Features
        document.addEventListener('click', function(e) {
            // Reply Toggle
            if(e.target.closest('.reply-toggle-btn')) {
                const btn = e.target.closest('.reply-toggle-btn');
                const commentId = btn.dataset.id;
                const form = document.getElementById(`reply-form-${commentId}`);
                form.classList.toggle('d-none');
            }

            // Submit Reply
            if(e.target.closest('.submit-reply-btn')) {
                const btn = e.target.closest('.submit-reply-btn');
                const parentId = btn.dataset.parentId;
                const articleId = document.getElementById('commentForm').dataset.articleId;
                const wrapper = btn.closest('.reply-input-wrapper');
                const content = wrapper.querySelector('.reply-content').value;

                if(!content.trim()) return;

                fetch(`/articles/${articleId}/comment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content, parent_id: parentId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        window.location.hash = 'commentsSection';
                        location.reload();
                    }
                });
            }

            // Like Comment
            if(e.target.closest('.like-comment-btn')) {
                const btn = e.target.closest('.like-comment-btn');
                const commentId = btn.dataset.id;
                const icon = btn.querySelector('i');
                const countSpan = btn.querySelector('.likes-count');

                fetch(`/comments/${commentId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        if(data.status === 'liked') {
                            icon.classList.replace('bi-heart', 'bi-heart-fill');
                            icon.classList.add('text-accent');
                        } else {
                            icon.classList.replace('bi-heart-fill', 'bi-heart');
                            icon.classList.remove('text-accent');
                        }
                        countSpan.innerText = data.likes_count;
                    }
                });
            }

            // Follow Toggle
            if(e.target.closest('.follow-btn')) {
                const btn = e.target.closest('.follow-btn');
                const userId = btn.dataset.id;

                fetch(`/follow/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        btn.innerText = data.status === 'followed' ? 'Following' : 'Follow';
                        btn.classList.toggle('btn-accent', data.status === 'followed');
                        btn.classList.toggle('text-navy', data.status === 'followed');
                        btn.classList.toggle('btn-outline-accent', data.status !== 'followed');
                        btn.classList.toggle('text-white', data.status !== 'followed');
                    }
                });
            }

            // Edit Comment Toggle
            if(e.target.closest('.edit-comment-btn')) {
                const btn = e.target.closest('.edit-comment-btn');
                const commentWrapper = btn.closest('.flex-grow-1');
                commentWrapper.querySelector('.comment-text').classList.add('d-none');
                commentWrapper.querySelector('.edit-wrapper').classList.remove('d-none');
            }

            // Cancel Edit
            if(e.target.closest('.cancel-edit-btn')) {
                const btn = e.target.closest('.cancel-edit-btn');
                const commentWrapper = btn.closest('.flex-grow-1');
                commentWrapper.querySelector('.comment-text').classList.remove('d-none');
                commentWrapper.querySelector('.edit-wrapper').classList.add('d-none');
            }

            // Save Edit
            if(e.target.closest('.save-edit-btn')) {
                const btn = e.target.closest('.save-edit-btn');
                const commentId = btn.dataset.id;
                const wrapper = btn.closest('.edit-wrapper');
                const content = wrapper.querySelector('.edit-content').value;

                fetch(`/comments/${commentId}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        const commentWrapper = btn.closest('.flex-grow-1');
                        commentWrapper.querySelector('.comment-text').innerText = content;
                        commentWrapper.querySelector('.comment-text').classList.remove('d-none');
                        commentWrapper.querySelector('.edit-wrapper').classList.add('d-none');
                    }
                });
            }

            // Delete Comment
            if(e.target.closest('.delete-comment-btn')) {
                if(!confirm('Are you sure you want to delete this?')) return;
                const btn = e.target.closest('.delete-comment-btn');
                const commentId = btn.dataset.id;

                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        btn.closest('.comment-item').remove();
                    }
                });
            }
        });

        // Like Button Logic
        document.getElementById('likeBtn').addEventListener('click', function() {
            const btn = this;
            const articleId = btn.getAttribute('data-id');
            const icon = btn.querySelector('i');
            const countSpan = document.getElementById('likesCount');

            fetch(`/articles/${articleId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.status === 'liked') {
                        icon.classList.replace('bi-heart', 'bi-heart-fill');
                    } else {
                        icon.classList.replace('bi-heart-fill', 'bi-heart');
                    }
                    countSpan.innerText = data.likes_count;
                }
            });
        });

        // Report Modal Logic
        const reasonSelect = document.getElementById('reportReasonSelect');
        const otherWrapper = document.getElementById('otherReasonWrapper');
        const otherInput = document.getElementById('reasonOtherInput');
        if(reasonSelect) {
            reasonSelect.addEventListener('change', function() {
                if(this.value === 'Other') {
                    otherWrapper.classList.remove('d-none');
                    otherInput.required = true;
                } else {
                    otherWrapper.classList.add('d-none');
                    otherInput.required = false;
                }
            });
        }

        // Enter to Submit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.classList.contains('submit-on-enter')) {
                e.preventDefault();
                const form = e.target.closest('form');
                if(form) form.dispatchEvent(new Event('submit'));
                
                // For reply inputs
                const replyBtn = e.target.closest('.reply-input-wrapper')?.querySelector('.submit-reply-btn');
                if(replyBtn) replyBtn.click();
            }
        });

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

    @if(session('success'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: 'success',
            title: 'Submitted!',
            text: "{{ session('success') }}",
            background: '#0d1e36',
            color: '#fff',
            iconColor: '#ffd60a'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            background: '#0d1e36',
            color: '#fff',
            iconColor: '#e63946'
        });
    </script>
    @endif

</body>

</html>