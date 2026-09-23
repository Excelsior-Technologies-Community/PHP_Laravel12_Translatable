<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Translation Manager</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .container-main {
            max-width: 1250px;
            margin: 40px auto;
        }

        .page-header {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            border-radius: 18px;
            padding: 30px;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .manager-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .translation-table th {
            white-space: nowrap;
        }

        .translation-table td {
            vertical-align: middle;
        }

        .language-box {
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 6px;
        }

        .available {
            background: #d1e7dd;
            color: #0f5132;
        }

        .missing {
            background: #f8d7da;
            color: #842029;
        }

        .partial {
            background: #fff3cd;
            color: #664d03;
        }

        .status-badge {
            font-size: 12px;
            padding: 7px 10px;
        }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
        }

        .post-id {
            font-weight: 700;
            color: #495057;
        }

        .author-name {
            font-weight: 600;
        }

        .translation-title {
            font-size: 13px;
            color: #6c757d;
            margin-top: 4px;
        }

        @media (max-width: 768px) {

            .container-main {
                margin: 20px auto;
            }

            .page-header {
                padding: 22px;
            }

            .manager-card {
                padding: 15px;
            }

        }
    </style>

</head>

<body>

    <div class="container container-main">

        {{-- Header --}}
        <div class="page-header">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                <div>

                    <h1>
                        🌐 Translation Manager
                    </h1>

                    <p class="mb-0">
                        Manage English and Hindi translations for all posts.
                    </p>

                </div>

                <div>

                    <span class="badge bg-light text-dark">

                        Current:
                        {{ strtoupper(app()->getLocale()) }}

                    </span>

                </div>

            </div>


            {{-- Action Buttons --}}
            <div class="action-buttons">

                {{-- All Posts --}}
                <a
                    href="{{ url('/') }}"
                    class="btn btn-light">

                    ← All Posts

                </a>


                {{-- Dashboard --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="btn btn-light">

                    📊 Dashboard

                </a>


                {{-- Add Post --}}
                <a
                    href="{{ url('/create') }}"
                    class="btn btn-warning">

                    ➕ Add Post

                </a>


                {{-- Export CSV --}}
                <a
                    href="{{ url('/posts/export/csv') }}"
                    class="btn btn-success">

                    📄 Export CSV

                </a>

            </div>

        </div>


        {{-- Flash Success Message --}}
        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Flash Error Message --}}
        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert">

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Translation Manager --}}
        <div class="manager-card">

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

                <div>

                    <h3 class="mb-1">
                        📋 Translation Overview
                    </h3>

                    <p class="text-muted mb-0">
                        Check translation availability and edit author/content together.
                    </p>

                </div>

                <div>

                    <span class="badge bg-primary">

                        {{ $posts->total() }} Posts

                    </span>

                </div>

            </div>


            @if($posts->count())

                <div class="table-responsive">

                    <table class="table table-hover translation-table">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Author
                                </th>

                                <th>
                                    English
                                </th>

                                <th>
                                    Hindi
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($posts as $post)

                                @php

                                    $english = $post->translations
                                        ->where('locale', 'en')
                                        ->first();

                                    $hindi = $post->translations
                                        ->where('locale', 'hi')
                                        ->first();

                                    $hasEnglish = $english
                                        && filled($english->title)
                                        && filled($english->content);

                                    $hasHindi = $hindi
                                        && filled($hindi->title)
                                        && filled($hindi->content);

                                    if ($hasEnglish && $hasHindi) {

                                        $status = 'Complete';

                                    } elseif ($hasEnglish || $hasHindi) {

                                        $status = 'Partial';

                                    } else {

                                        $status = 'Missing';

                                    }

                                @endphp


                                <tr>

                                    {{-- ID --}}
                                    <td>

                                        <span class="post-id">

                                            #{{ $post->id }}

                                        </span>

                                    </td>


                                    {{-- Author --}}
                                    <td>

                                        <span class="author-name">

                                            {{ $post->author }}

                                        </span>

                                    </td>


                                    {{-- English --}}
                                    <td>

                                        @if($hasEnglish)

                                            <div class="language-box available">

                                                <strong>
                                                    🇬🇧 Available
                                                </strong>

                                                <div class="translation-title">

                                                    {{ \Illuminate\Support\Str::limit(
                                                        $english->title,
                                                        45
                                                    ) }}

                                                </div>

                                            </div>

                                        @else

                                            <div class="language-box missing">

                                                <strong>
                                                    🇬🇧 Missing
                                                </strong>

                                                <div class="translation-title">

                                                    English translation required

                                                </div>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Hindi --}}
                                    <td>

                                        @if($hasHindi)

                                            <div class="language-box available">

                                                <strong>
                                                    🇮🇳 Available
                                                </strong>

                                                <div class="translation-title">

                                                    {{ \Illuminate\Support\Str::limit(
                                                        $hindi->title,
                                                        45
                                                    ) }}

                                                </div>

                                            </div>

                                        @else

                                            <div class="language-box missing">

                                                <strong>
                                                    🇮🇳 Missing
                                                </strong>

                                                <div class="translation-title">

                                                    Hindi translation required

                                                </div>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($status === 'Complete')

                                            <span
                                                class="badge bg-success status-badge">

                                                ✓ Complete

                                            </span>

                                        @elseif($status === 'Partial')

                                            <span
                                                class="badge bg-warning text-dark status-badge">

                                                ⚠ Partial

                                            </span>

                                        @else

                                            <span
                                                class="badge bg-danger status-badge">

                                                ✕ Missing

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-center">

                                        <a
                                            href="{{ route('translations.edit', $post) }}"
                                            class="btn btn-sm btn-primary">

                                            ✏️ Edit Post & Translation

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($posts->hasPages())

                    <div class="d-flex justify-content-center mt-4">

                        {{ $posts->links('pagination::bootstrap-5') }}

                    </div>

                @endif


            @else

                {{-- Empty State --}}
                <div class="empty-state">

                    <div class="display-4 mb-3">
                        🌐
                    </div>

                    <h4>
                        No posts found
                    </h4>

                    <p class="text-muted">

                        Create a post to start managing translations.

                    </p>


                    {{-- Create First Post --}}
                    <a
                        href="{{ url('/create') }}"
                        class="btn btn-primary">

                        ➕ Create First Post

                    </a>

                </div>

            @endif

        </div>


        {{-- Status Guide --}}
        <div class="manager-card mt-4">

            <h4 class="mb-3">

                📖 Translation Status Guide

            </h4>


            <div class="row g-3">

                {{-- Complete --}}
                <div class="col-md-4">

                    <div class="alert alert-success mb-0">

                        <strong>
                            ✓ Complete
                        </strong>

                        <div class="small mt-1">

                            Both English and Hindi translations
                            contain title and content.

                        </div>

                    </div>

                </div>


                {{-- Partial --}}
                <div class="col-md-4">

                    <div class="alert alert-warning mb-0">

                        <strong>
                            ⚠ Partial
                        </strong>

                        <div class="small mt-1">

                            Only one language has a complete
                            translation.

                        </div>

                    </div>

                </div>


                {{-- Missing --}}
                <div class="col-md-4">

                    <div class="alert alert-danger mb-0">

                        <strong>
                            ✕ Missing
                        </strong>

                        <div class="small mt-1">

                            Neither English nor Hindi has
                            complete translation data.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>
