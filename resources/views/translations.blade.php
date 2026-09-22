<!DOCTYPE html>
<html>

<head>

    <title>Translation Completeness Manager</title>

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
            margin-bottom: 20px;
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

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .translation-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .languages {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .language {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .language h3 {
            margin-top: 0;
        }

        .available {
            color: #28a745;
            font-weight: bold;
        }

        .missing {
            color: #dc3545;
            font-weight: bold;
        }

        .progress {
            height: 12px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-bar {
            height: 100%;
            background: #007bff;
        }

        .edit {
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .pagination {
            margin-top: 20px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h1>📝 Translation Completeness Manager</h1>

        <p>
            Manage English and Hindi translations for every post.
        </p>

        <a href="{{ route('posts.index') }}"
           class="btn btn-primary">
            Posts
        </a>

        <a href="{{ route('dashboard') }}"
           class="btn btn-secondary">
            Dashboard
        </a>

    </div>


    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif


    @forelse($posts as $post)

        @php

            $english = $post->translations
                ->where('locale', 'en')
                ->first();

            $hindi = $post->translations
                ->where('locale', 'hi')
                ->first();

            $englishExists = !is_null($english);
            $hindiExists = !is_null($hindi);

            $percentage = 0;

            if ($englishExists) {
                $percentage += 50;
            }

            if ($hindiExists) {
                $percentage += 50;
            }

        @endphp


        <div class="translation-card">

            <div class="top">

                <div>

                    <h2>
                        Post #{{ $post->id }}
                    </h2>

                    <p>
                        Author:
                        {{ $post->author ?? 'N/A' }}
                    </p>

                </div>


                <a href="{{ route('translations.edit', $post) }}"
                   class="edit">
                    ✏️ Manage Translation
                </a>

            </div>


            <div class="languages">

                {{-- English --}}

                <div class="language">

                    <h3>🇬🇧 English</h3>

                    @if($englishExists)

                        <p class="available">
                            ✓ Translation Available
                        </p>

                        <strong>
                            {{ $english->title }}
                        </strong>

                    @else

                        <p class="missing">
                            ✗ Translation Missing
                        </p>

                    @endif

                </div>


                {{-- Hindi --}}

                <div class="language">

                    <h3>🇮🇳 Hindi</h3>

                    @if($hindiExists)

                        <p class="available">
                            ✓ Translation Available
                        </p>

                        <strong>
                            {{ $hindi->title }}
                        </strong>

                    @else

                        <p class="missing">
                            ✗ Translation Missing
                        </p>

                    @endif

                </div>

            </div>


            <div style="margin-top:20px">

                <strong>
                    Translation Completion:
                    {{ $percentage }}%
                </strong>

                <div class="progress">

                    <div
                        class="progress-bar"
                        style="width: {{ $percentage }}%">
                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="translation-card">

            <h3>No posts available.</h3>

        </div>

    @endforelse


    <div class="pagination">

        {{ $posts->links() }}

    </div>

</div>

</body>

</html>