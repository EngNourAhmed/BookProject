<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Writer Profile</title>
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .profile-container {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }
        .profile-cover {
            height: 350px;
            background: linear-gradient(135deg, #0d1e36 0%, #020710 100%);
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            position: relative;
            overflow: hidden;
            border-bottom: 2px solid var(--accent-yellow);
        }
        .profile-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=2073&ixlib=rb-4.0.3') center/cover;
            opacity: 0.3;
            filter: grayscale(50%) contrast(120%);
        }
        .avatar-overlap {
            width: 160px;
            height: 160px;
            background: var(--bg-navy);
            border: 5px solid var(--bg-navy);
            border-radius: 50%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            margin: 0 auto;
        }
        .avatar-overlap .inner-avatar {
            width: 100%;
            height: 100%;
            background: rgba(255, 214, 10, 0.1);
            border: 2px solid var(--accent-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--accent-yellow);
        }
        .profile-card {
            background: rgba(13, 30, 54, 0.6);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-xl);
            padding: 5rem 2rem 3rem;
            text-center;
        }
        .stat-item {
            padding: 1rem 2rem;
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            min-width: 140px;
            transition: all 0.3s ease;
        }
        .stat-item:hover {
            background: rgba(255, 214, 10, 0.05);
            border-color: var(--accent-yellow);
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-navy">
    <div class="profile-cover">
        <div class="container h-100 position-relative">
            <a href="{{ url()->previous() }}" class="btn btn-saas-secondary position-absolute top-0 start-0 mt-4 ms-3" style="z-index: 100;">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="container profile-container mb-5">
        <div class="avatar-overlap">
            <div class="inner-avatar">
                <i class="bi bi-person"></i>
            </div>
        </div>

        <div class="profile-card mt-n5 text-center">
            <div class="max-w-700 mx-auto">
                <h1 class="text-white display-5 fw-bold mb-2">{{ $user->name }}</h1>
                <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
                    <span class="badge bg-accent-10 text-accent px-3 py-2 rounded-pill text-uppercase small fw-bold">Verified Writer</span>
                    <span class="text-white-50 small">• Since {{ $user->created_at->format('M Y') }}</span>
                </div>

                @if(auth()->id() !== $user->id)
                <div class="mb-5">
                    <button class="btn {{ auth()->user()->isFollowing($user->id) ? 'btn-accent text-navy' : 'btn-outline-accent text-white' }} fw-bold rounded-pill px-5 py-3 follow-btn shadow-lg" data-id="{{ $user->id }}" style="border-width: 2px; font-size: 1.1rem;">
                        <i class="bi {{ auth()->user()->isFollowing($user->id) ? 'bi-check-circle-fill' : 'bi-plus-lg' }} me-2"></i>
                        {{ auth()->user()->isFollowing($user->id) ? 'Following' : 'Follow Writer' }}
                    </button>
                    <a href="{{ route('messages.index', ['user_id' => $user->id]) }}" class="btn btn-outline-white rounded-pill px-4 py-3 ms-3" style="font-size: 1.1rem;">
                        <i class="bi bi-chat-dots me-2"></i> Message
                    </a>
                </div>
                @else
                <div class="mb-5">
                    <button class="btn btn-outline-accent rounded-pill px-4 py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil-square me-2"></i> Edit Profile
                    </button>
                </div>
                @endif

                <p class="text-white-80 fs-5 mb-5 lh-base">
                    {{ $user->bio ?? 'Passionate about sharing insights and stories with the Book ERA community. Dedicated to the craft of storytelling and literary exploration.' }}
                </p>

                <div class="d-flex justify-content-center gap-4 flex-wrap mb-2">
                    <div class="stat-item">
                        <h3 class="text-white fw-bold mb-0">{{ $articles->total() }}</h3>
                        <small class="text-white-50 text-uppercase letter-spacing-1">Articles</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-white fw-bold mb-0" id="followersCount">{{ $user->followers->count() }}</h3>
                        <small class="text-white-50 text-uppercase letter-spacing-1">Followers</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="text-white fw-bold mb-0">Published Works</h3>
                <div class="text-white-50 small text-uppercase fw-bold">{{ $articles->total() }} total</div>
            </div>
            
            <div class="row g-4">
                @forelse($articles as $article)
                <div class="col-md-4">
                    <div class="premium-card p-4 h-100 d-flex flex-column border-white-5">
                        <div class="mb-3">
                             <span class="badge bg-white-5 text-white-50 x-small rounded-pill px-2 py-1">Article</span>
                        </div>
                        <h5 class="text-white fw-bold mb-3 line-clamp-2" style="font-family: 'Playfair Display', serif;">{{ $article->title }}</h5>
                        <p class="text-white-50 small mb-4 flex-grow-1">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                        </p>
                        <div class="mt-auto pt-3 border-top border-white-5">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-white-50 x-small"><i class="bi bi-calendar3 me-1"></i> {{ $article->created_at->format('M d, Y') }}</span>
                                <span class="text-white-50 x-small"><i class="bi bi-heart-fill text-accent me-1"></i> {{ $article->likes->count() }}</span>
                            </div>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-saas-primary w-100 py-2 rounded-3 fw-bold">Read Full Story</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-20 mb-3"><i class="bi bi-book fs-1 text-white"></i></div>
                    <p class="text-white-50">This writer hasn't published any stories yet.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $articles->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
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
                        const iconClass = data.status === 'followed' ? 'bi-check-circle-fill' : 'bi-plus-lg';
                        btn.innerHTML = `<i class="bi ${iconClass} me-2"></i> ${data.status === 'followed' ? 'Following' : 'Follow Writer'}`;
                        btn.classList.toggle('btn-accent', data.status === 'followed');
                        btn.classList.toggle('text-navy', data.status === 'followed');
                        btn.classList.toggle('btn-outline-accent', data.status !== 'followed');
                        btn.classList.toggle('text-white', data.status !== 'followed');
                        
                        const countEl = document.getElementById('followersCount');
                        if(countEl) countEl.innerText = data.followers_count;
                    }
                });
            }
        });

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
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-navy border-white-10 text-white rounded-4">
                <form action="{{ route('writer.profile.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom border-white-5 p-4">
                        <h5 class="modal-title fw-bold">Edit Profile</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-white-50 small text-uppercase">Name</label>
                            <input type="text" name="name" class="form-control bg-white-5 border-white-10 text-white" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small text-uppercase">Email</label>
                            <input type="email" name="email" class="form-control bg-white-5 border-white-10 text-white" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small text-uppercase">Phone</label>
                            <input type="text" name="phone" class="form-control bg-white-5 border-white-10 text-white" value="{{ $user->phone }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small text-uppercase">Bio</label>
                            <textarea name="bio" class="form-control bg-white-5 border-white-10 text-white" rows="4">{{ $user->bio }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-white-5 p-4">
                        <button type="button" class="btn btn-saas-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-saas-primary px-4 py-2 fw-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
