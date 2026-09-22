<!DOCTYPE html>
<html>

<head>

    <title>Translation Dashboard</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 1100px;
            max-width: 95%;
            margin: 40px auto;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
        }

        .nav {
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            margin-right: 5px;
        }

        .btn-primary {
            background: #007bff;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .card h3 {
            margin-top: 0;
            color: #555;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
        }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
        }

        .complete {
            background: #d4edda;
            color: #155724;
        }

        .partial {
            background: #fff3cd;
            color: #856404;
        }

        .missing {
            background: #f8d7da;
            color: #721c24;
        }

        .pagination {
            margin-top: 20px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h1>📊 Multilingual Translation Dashboard</h1>

        <div class="nav">

            <a href="{{ route('posts.index') }}"
               class="btn btn-primary">
                Posts
            </a>

            <a href="{{ route('translations') }}"
               class="btn btn-warning">
                Translation Manager
            </a>

            <a href="{{ route('posts.create') }}"
               class="btn btn-secondary">
                Create Post
            </a>

        </div>

    </div>


    {{-- Statistics --}}

    <div class="stats">

        <div class="card">

            <h3>Total Posts</h3>

            <div class="number">
                {{ $totalPosts }}
            </div>

        </div>


        <div class="card">

            <h3>English Translations</h3>

            <div class="number">
                {{ $englishTranslations }}
            </div>

        </div>


        <div class="card">

            <h3>Hindi Translations</h3>

            <div class="number">
                {{ $hindiTranslations }}
            </div>

        </div>


        <div class="card">

            <h3>Fully Translated</h3>

            <div class="number">
                {{ $fullyTranslated }}
            </div>

        </div>


        <div class="card">

            <h3>Partially Translated</h3>

            <div class="number">
                {{ $partiallyTranslated }}
            </div>

        </div>


        <div class="card">

            <h3>Missing Translations</h3>

            <div class="number">
                {{ $missingTranslations }}
            </div>

        </div>

    </div>


    {{-- Translation Overview --}}

    <div class="table-box">

        <h2>Translation Overview</h2>

        <table>

            <thead>

                <tr>
                    <th>Post</th>
                    <th>Author</th>
                    <th>English</th>
                    <th>Hindi</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

            @forelse($posts as $post)

                @php

                    $hasEnglish = $post->translations
                        ->where('locale', 'en')
                        ->isNotEmpty();

                    $hasHindi = $post->translations
                        ->where('locale', 'hi')
                        ->isNotEmpty();

                @endphp

                <tr>

                    <td>
                        {{ $post->id }}
                    </td>

                    <td>
                        {{ $post->author ?? 'N/A' }}
                    </td>

                    <td>

                        @if($hasEnglish)

                            <span class="badge complete">
                                ✓ Available
                            </span>

                        @else

                            <span class="badge missing">
                                ✗ Missing
                            </span>

                        @endif

                    </td>

                    <td>

                        @if($hasHindi)

                            <span class="badge complete">
                                ✓ Available
                            </span>

                        @else

                            <span class="badge missing">
                                ✗ Missing
                            </span>

                        @endif

                    </td>

                    <td>

                        @if($hasEnglish && $hasHindi)

                            <span class="badge complete">
                                Fully Translated
                            </span>

                        @else

                            <span class="badge partial">
                                Partial Translation
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5">
                        No posts available.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>


        <div class="pagination">

            {{ $posts->links() }}

        </div>

    </div>

</div>

</body>

</html>