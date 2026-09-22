<!DOCTYPE html>
<html>

<head>
    <title>Multilingual Posts</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 1000px;
            max-width: 95%;
            margin: 40px auto;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 9px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-primary {
            background: #007bff;
        }

        .btn-dark {
            background: #343a40;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn:hover {
            opacity: 0.85;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-form input,
        .search-form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form input {
            flex: 1;
            min-width: 250px;
        }

        .post-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        .post-title {
            margin-top: 0;
            color: #222;
        }

        .author {
            color: #666;
            font-size: 14px;
        }

        .locale {
            font-size: 13px;
            color: #777;
            margin-top: 10px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .empty {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
        }

        .pagination .active {
            background: #007bff;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">

            <div class="top-bar">

                <div class="buttons">

                    <a href="{{ route('language.change', 'en') }}"
                        class="btn btn-secondary">
                        English
                    </a>

                    <a href="{{ route('language.change', 'hi') }}"
                        class="btn btn-secondary">
                        Hindi
                    </a>

                    <a href="{{ route('dashboard') }}"
                        class="btn btn-dark">
                        📊 Dashboard
                    </a>

                    <a href="{{ route('translations') }}"
                        class="btn btn-warning">
                        📝 Translations
                    </a>

                </div>

                <a href="{{ route('posts.create') }}"
                    class="btn btn-success">
                    + Create Post
                </a>

            </div>

            <div class="locale">
                Current Language:
                <strong>{{ app()->getLocale() }}</strong>
            </div>

        </div>


        @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

        @endif


        {{-- Search & Filtering --}}

        <div class="search-box">

            <h3>🔎 Multilingual Search & Filtering</h3>

            <form method="GET"
                action="{{ route('posts.index') }}"
                class="search-form">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search title, content or author...">

                <select name="language">

                    <option value="current"
                        {{ $language === 'current' ? 'selected' : '' }}>
                        Current Language
                    </option>

                    <option value="all"
                        {{ $language === 'all' ? 'selected' : '' }}>
                        All Languages
                    </option>

                    <option value="en"
                        {{ $language === 'en' ? 'selected' : '' }}>
                        English
                    </option>

                    <option value="hi"
                        {{ $language === 'hi' ? 'selected' : '' }}>
                        Hindi
                    </option>

                </select>

                <button type="submit"
                    class="btn btn-primary">
                    Search
                </button>

                <a href="{{ route('posts.index') }}"
                    class="btn btn-secondary">
                    Reset
                </a>

            </form>

        </div>


        @forelse($posts as $post)

        <div class="post-card">

            <h2 class="post-title">
                {{ $post->title }}
            </h2>

            <p>
                {{ $post->content }}
            </p>

            @if($post->author)

            <div class="author">
                Author: {{ $post->author }}
            </div>

            @endif

            <div class="locale">
                Displayed Language:
                <strong>{{ app()->getLocale() }}</strong>
            </div>

        </div>

        @empty

        <div class="empty">

            <h3>No Posts Found</h3>

            <p>
                Try another search term or language.
            </p>

        </div>

        @endforelse


        {{-- Pagination --}}

        @if($posts->hasPages())

        <div class="pagination">
            {{ $posts->links() }}
        </div>

        @endif

    </div>

</body>

</html>