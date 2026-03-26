<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Drafts - Book ERA</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SaaS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
                    <i class="bi bi-file-earmark-text text-accent" style="font-size: 15rem;"></i>
                </div>
                <div class="position-relative z-1">
                    <h2 class="text-white fw-bold mb-1">My Drafts</h2>
                    <p class="text-white-50 small mb-0">Continue working on your unpublished masterpieces.</p>
                </div>
            </div>
        </div>

        <div class="users-table-container mt-4">
            <div class="table-header mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fs-4 text-white fw-bold mb-1">Manage Drafts</h2>
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
                                <th class="ps-4 py-3">Title & Summary</th>
                                <th class="text-center py-3">Status</th>
                                <th class="text-center py-3">Last Saved</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                            <tr class="align-middle border-bottom border-white-5 border-opacity-5">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="article-icon bg-white-5 rounded-3 p-2 me-3">
                                            <i class="bi bi-file-earmark-text text-accent"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-white mb-0">{{ $article->title }}</div>
                                            <div class="text-white-50 x-small">{{ Str::limit(strip_tags($article->content), 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill x-small border border-secondary border-opacity-25">Draft</span>
                                </td>
                                <td class="text-center">
                                    <div class="text-white small">{{ $article->updated_at->format('M d, Y') }}</div>
                                    <div class="text-white-50 xx-small">{{ $article->updated_at->diffForHumans() }}</div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Edit Action can be handled via modal or separate edit page. Assuming standard edit pattern here -->
                                        <button class="btn btn-sm btn-glass text-white border-white-10 text-accent" title="Edit Draft" onclick="alert('Editing drafts will be supported soon!')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-white-50">
                                    <div class="py-4">
                                        <i class="bi bi-file-earmark-x fs-1 d-block mb-3 opacity-25"></i>
                                        <p class="mb-4">You don't have any drafts right now.</p>
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
</body>
</html>
