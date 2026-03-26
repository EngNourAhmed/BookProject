<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ $article->title }}</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-4">

        <div class="card shadow-lg border-0">
            <div class="card-body p-4">

                {{-- عنوان المقال --}}
                <h1 class="fw-bold mb-3">{{ $article->title }}</h1>

                {{-- معلومات الكاتب --}}
                <div class="d-flex align-items-center mb-4">
                 

                    <div>
                        <h6 class="mb-0">{{ $article->user->name }}</h6>
                        <small class="text-muted">
                            نُشر بتاريخ {{ $article->created_at->format('Y-m-d') }}
                        </small>
                    </div>
                </div>

                <hr>

                {{-- محتوى المقال --}}
                <div class="article-content fs-5" style="line-height: 1.9">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <hr class="my-4">

                {{-- حالة المقال --}}
                <div class="mt-3">
                    <span class="badge
                    @if($article->status == 'approved') bg-success
                    @elseif($article->status == 'pending') bg-warning
                    @else bg-danger @endif
                fs-6 px-3 py-2">
                        حالة المقال: {{ $article->status }}
                    </span>
                </div>

            </div>
        </div>

        {{-- زر العودة --}}
        <div class="text-center mt-4">
            <a href="{{ route('articles.index') }}" class="btn btn-secondary px-4 py-2">
                الرجوع للقائمة
            </a>
        </div>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>