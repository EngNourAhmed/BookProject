<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reader Dashboard - Book ERA</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Google Fonts for Books Vibes -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- ملف CSS مخصص -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/330/330731.png" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        .article-card {
            background: rgba(13, 30, 54, 0.4); /* Transparent glass */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-left: 6px solid var(--accent-yellow);
            border-radius: 4px 16px 16px 4px;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            box-shadow: 12px 12px 40px rgba(0,0,0,0.5);
            height: 100%;
            display: flex;
            flex-direction: column;
            border-top: 1px solid rgba(255,255,255,0.08);
            border-right: 1px solid rgba(255,255,255,0.08);
        }
        
        .article-card:hover {
            transform: translateY(-10px) rotate(-1deg);
            box-shadow: 25px 25px 60px rgba(0, 0, 0, 0.6), 0 0 20px rgba(255, 214, 10, 0.1);
        }
        
        .article-card::after {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(0,0,0,0.3);
            box-shadow: 1px 0 3px rgba(255,255,255,0.05);
        }

        .card-body {
            padding: 2.5rem !important;
        }

        .article-title {
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.01em;
            line-height: 1.2;
            font-size: 1.7rem;
            margin-bottom: 1.5rem !important;
            color: #ffffff !important;
            font-weight: 800;
        }
        
        .category-badge {
            font-family: 'Inter', sans-serif;
            background: rgba(255, 214, 10, 0.1);
            color: var(--accent-yellow);
            font-size: 0.6rem;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 2rem;
            display: inline-block;
            border: 1px solid rgba(255, 214, 10, 0.2);
        }
        
        .read-more-btn {
            background: var(--accent-yellow);
            color: #040d1a !important;
            border: none;
            padding: 10px 22px;
            border-radius: 2px;
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        
        .read-more-btn:hover {
            background: #ffffff;
            color: #040d1a !important;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .author-name {
            font-size: 0.9rem;
            color: #ffffff;
            font-weight: 700;
        }
        
        .publish-date {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
        }
    </style>
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
            <div class="d-flex align-items-center justify-content-between bg-glass p-4 rounded-4 border border-white-5 border-opacity-10">
                <div>
                    <h2 class="text-white fw-bold mb-1">Article Feed</h2>
                    <p class="text-white-50 small mb-0">Discover the latest insights from our <span class="text-accent">premium</span> writers.</p>
                </div>
                <div class="d-none d-md-block opacity-25 text-white">
                    <i class="bi bi-book fs-1"></i>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="row g-4">
                @forelse($articles as $article)
                <div class="col-xl-4 col-md-6">
                    <div class="article-card">
                        <div class="card-body d-flex flex-column">
                            <span class="category-badge">Edition: Exclusive</span>
                            <h3 class="article-title">{{ Str::limit($article->title, 60) }}</h3>
                            <p class="text-white-50 small mb-4 flex-grow-1">
                                {{ Str::limit(strip_tags($article->content), 120) }}
                            </p>
                            
                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                <div class="author-info d-flex align-items-center">
                                    <div class="avatar-sm me-2" style="width: 32px; height: 32px; background: rgba(255, 214, 10, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person text-accent"></i>
                                    </div>
                                    <div>
                                        <p class="author-name fw-bold mb-0 text-white">{{ $article->user->name }}</p>
                                        <p class="publish-date mb-0">{{ $article->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('articles.show', $article->id) }}" class="btn read-more-btn">
                                    Open Chapter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="text-white-50 opacity-25 mb-4">
                        <i class="bi bi-journal-x" style="font-size: 5rem;"></i>
                    </div>
                    <h3 class="text-white fw-bold">No articles found</h3>
                    <p class="text-white-50">Check back later for fresh content!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

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
    </script>
</body>
</html>
