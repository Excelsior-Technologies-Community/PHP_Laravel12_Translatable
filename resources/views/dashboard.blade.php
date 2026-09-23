<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Translation Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border-radius: 18px;
            padding: 30px;
            margin-bottom: 25px;
        }

        .dashboard-header h1 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-card {
            border: 0;
            border-radius: 16px;
            padding: 25px;
            height: 100%;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            transition: 0.2s ease;
        }

        .stat-number {
            font-size: 34px;
            font-weight: 700;
        }

        .stat-label {
            color: #6b7280;
            font-size: 15px;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .progress {
            height: 10px;
            border-radius: 20px;
        }

        .post-row {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .post-row:last-child {
            border-bottom: 0;
        }

        .post-row:hover {
            background: #fafafa;
        }

        .badge-status {
            font-size: 12px;
            padding: 7px 10px;
        }

        .top-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .language-badge {
            font-size: 12px;
            padding: 6px 9px;
        }

    </style>

</head>

<body>

<div class="container dashboard-container">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="dashboard-header">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div>

                <h1>
                    🌐 Translation Dashboard
                </h1>

                <p class="mb-0">
                    Monitor English and Hindi translation coverage for all posts.
                </p>

            </div>

            <div class="text-end">

                <span class="badge bg-light text-dark">

                    Current Language:

                    {{ strtoupper(app()->getLocale()) }}

                </span>

            </div>

        </div>


        <div class="top-actions">

            {{-- All Posts --}}
            <a
                href="{{ url('/') }}"
                class="btn btn-light">

                ← All Posts

            </a>


            {{-- Create Post --}}
            {{-- Direct URL avoids undefined route-name error --}}
            <a
                href="{{ url('/create') }}"
                class="btn btn-light">

                ➕ Add Post

            </a>


            {{-- Translation Manager --}}
            <a
                href="{{ route('translations') }}"
                class="btn btn-warning">

                📝 Translation Manager

            </a>


            {{-- CSV Export --}}
            <a
                href="{{ url('/posts/export/csv') }}"
                class="btn btn-success">

                📄 Export CSV

            </a>

        </div>

    </div>


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="row g-4">

        {{-- Total Posts --}}
        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="stat-number text-primary">
                    {{ $totalPosts }}
                </div>

                <div class="stat-label">
                    Total Posts
                </div>

            </div>

        </div>


        {{-- English --}}
        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="stat-number text-success">
                    {{ $englishTranslations }}
                </div>

                <div class="stat-label">
                    English Translations
                </div>

            </div>

        </div>


        {{-- Hindi --}}
        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="stat-number text-warning">
                    {{ $hindiTranslations }}
                </div>

                <div class="stat-label">
                    Hindi Translations
                </div>

            </div>

        </div>


        {{-- Fully Translated --}}
        <div class="col-md-6 col-lg-3">

            <div class="card stat-card">

                <div class="stat-number text-info">
                    {{ $fullyTranslated }}
                </div>

                <div class="stat-label">
                    Fully Translated
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TRANSLATION COVERAGE
    ========================================================== --}}

    <div class="section-card">

        <h3 class="mb-4">
            📊 Translation Coverage
        </h3>


        @php

            $total = max((int) $totalPosts, 1);

            $englishPercentage = round(
                ($englishTranslations / $total) * 100
            );

            $hindiPercentage = round(
                ($hindiTranslations / $total) * 100
            );

            $completePercentage = round(
                ($fullyTranslated / $total) * 100
            );

            $englishPercentage = min($englishPercentage, 100);

            $hindiPercentage = min($hindiPercentage, 100);

            $completePercentage = min($completePercentage, 100);

        @endphp


        {{-- English --}}
        <div class="mb-4">

            <div class="d-flex justify-content-between mb-2">

                <strong>
                    🇬🇧 English
                </strong>

                <span>

                    {{ $englishTranslations }}

                    /

                    {{ $totalPosts }}

                    ({{ $englishPercentage }}%)

                </span>

            </div>


            <div class="progress">

                <div
                    class="progress-bar bg-success"
                    role="progressbar"
                    style="width: {{ $englishPercentage }}%;">

                </div>

            </div>

        </div>


        {{-- Hindi --}}
        <div class="mb-4">

            <div class="d-flex justify-content-between mb-2">

                <strong>
                    🇮🇳 Hindi
                </strong>

                <span>

                    {{ $hindiTranslations }}

                    /

                    {{ $totalPosts }}

                    ({{ $hindiPercentage }}%)

                </span>

            </div>


            <div class="progress">

                <div
                    class="progress-bar bg-warning"
                    role="progressbar"
                    style="width: {{ $hindiPercentage }}%;">

                </div>

            </div>

        </div>


        {{-- Complete --}}
        <div>

            <div class="d-flex justify-content-between mb-2">

                <strong>
                    ✅ Complete Translation
                </strong>

                <span>

                    {{ $fullyTranslated }}

                    /

                    {{ $totalPosts }}

                    ({{ $completePercentage }}%)

                </span>

            </div>


            <div class="progress">

                <div
                    class="progress-bar bg-info"
                    role="progressbar"
                    style="width: {{ $completePercentage }}%;">

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TRANSLATION STATUS
    ========================================================== --}}

    <div class="section-card">

        <h3 class="mb-4">
            🔎 Translation Status
        </h3>


        <div class="row g-3">

            {{-- Complete --}}
            <div class="col-md-4">

                <div class="alert alert-success mb-0">

                    <strong>
                        ✓ Complete
                    </strong>

                    <div class="fs-4 mt-1">
                        {{ $fullyTranslated }}
                    </div>

                    <small>
                        English + Hindi available
                    </small>

                </div>

            </div>


            {{-- Partial --}}
            <div class="col-md-4">

                <div class="alert alert-warning mb-0">

                    <strong>
                        ⚠ Partial
                    </strong>

                    <div class="fs-4 mt-1">
                        {{ $partiallyTranslated }}
                    </div>

                    <small>
                        One translation available
                    </small>

                </div>

            </div>


            {{-- Missing --}}
            <div class="col-md-4">

                <div class="alert alert-danger mb-0">

                    <strong>
                        ✕ Missing
                    </strong>

                    <div class="fs-4 mt-1">
                        {{ $missingTranslations }}
                    </div>

                    <small>
                        No translations available
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TRANSLATION OVERVIEW
    ========================================================== --}}

    <div class="section-card">

        <div
            class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

            <div>

                <h3 class="mb-1">
                    📋 Translation Overview
                </h3>

                <p class="text-muted mb-0">
                    Review translation status for each post.
                </p>

            </div>


            <a
                href="{{ route('translations') }}"
                class="btn btn-primary btn-sm">

                Manage Translations

            </a>

        </div>


        @if($posts->count())

            @foreach($posts as $post)

                @php

                    $english = $post->translations
                        ->where('locale', 'en')
                        ->first();

                    $hindi = $post->translations
                        ->where('locale', 'hi')
                        ->first();


                    $hasEnglish =
                        $english &&
                        filled($english->title) &&
                        filled($english->content);


                    $hasHindi =
                        $hindi &&
                        filled($hindi->title) &&
                        filled($hindi->content);


                    if ($hasEnglish && $hasHindi) {

                        $status = 'Complete';

                    } elseif ($hasEnglish || $hasHindi) {

                        $status = 'Partial';

                    } else {

                        $status = 'Missing';

                    }

                @endphp


                <div class="post-row">

                    <div class="row align-items-center g-3">

                        {{-- ID --}}
                        <div class="col-md-1">

                            <strong>
                                #{{ $post->id }}
                            </strong>

                        </div>


                        {{-- Author --}}
                        <div class="col-md-3">

                            <strong>
                                {{ $post->author }}
                            </strong>

                            <div class="text-muted small">
                                Author
                            </div>

                        </div>


                        {{-- Languages --}}
                        <div class="col-md-3">

                            <div class="mb-1">

                                @if($hasEnglish)

                                    <span
                                        class="badge bg-success language-badge">

                                        EN ✓

                                    </span>

                                @else

                                    <span
                                        class="badge bg-secondary language-badge">

                                        EN —

                                    </span>

                                @endif


                                @if($hasHindi)

                                    <span
                                        class="badge bg-warning text-dark language-badge">

                                        HI ✓

                                    </span>

                                @else

                                    <span
                                        class="badge bg-secondary language-badge">

                                        HI —

                                    </span>

                                @endif

                            </div>


                            <div class="small text-muted">
                                Translation availability
                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-3">

                            @if($status === 'Complete')

                                <span
                                    class="badge bg-success badge-status">

                                    ✓ Complete

                                </span>

                            @elseif($status === 'Partial')

                                <span
                                    class="badge bg-warning text-dark badge-status">

                                    ⚠ Partial

                                </span>

                            @else

                                <span
                                    class="badge bg-danger badge-status">

                                    ✕ Missing

                                </span>

                            @endif

                        </div>


                        {{-- Edit --}}
                        <div class="col-md-2 text-md-end">

                            <a
                                href="{{ route('translations.edit', $post) }}"
                                class="btn btn-sm btn-outline-primary">

                                ✏️ Edit

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach


            {{-- Pagination --}}
            @if($posts->hasPages())

                <div class="mt-4">

                    {{ $posts->links('pagination::bootstrap-5') }}

                </div>

            @endif


        @else

            <div class="alert alert-info mb-0">

                No posts are available yet.

            </div>

        @endif

    </div>


    {{-- =========================================================
         STATUS GUIDE
    ========================================================== --}}

    <div class="section-card">

        <h3 class="mb-3">
            💡 Translation Status Guide
        </h3>


        <p class="mb-2">

            <span class="badge bg-success">
                Complete
            </span>

            Both English and Hindi translations are available.

        </p>


        <p class="mb-2">

            <span class="badge bg-warning text-dark">
                Partial
            </span>

            Only one language has a complete translation.

        </p>


        <p class="mb-0">

            <span class="badge bg-danger">
                Missing
            </span>

            Neither English nor Hindi translation is available.

        </p>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>
