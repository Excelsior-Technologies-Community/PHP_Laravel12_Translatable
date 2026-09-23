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
            width: 1150px;
            max-width: 95%;
            margin: 40px auto;
        }

        .header,
        .search-box,
        .post-card,
        .bulk-box,
        .export-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            padding: 20px;
            margin-bottom: 20px;
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
            display: inline-block;
            padding: 9px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #007bff;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-dark {
            background: #343a40;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-info {
            background: #17a2b8;
        }

        .btn:hover {
            opacity: .85;
        }

        .locale {
            color: #777;
            font-size: 13px;
            margin-top: 10px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .search-box {
            padding: 20px;
            margin-bottom: 20px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto auto;
            gap: 10px;
        }

        .search-grid input,
        .search-grid select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .bulk-box {
            padding: 15px;
            margin-bottom: 20px;
        }

        .bulk-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .export-box {
            padding: 15px;
            margin-bottom: 20px;
        }

        .post-card {
            padding: 20px;
            margin-bottom: 15px;
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
        }

        .post-title {
            margin-top: 0;
            color: #222;
        }

        .author {
            color: #666;
            font-size: 14px;
        }

        .post-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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

        .checkbox {
            width: 18px;
            height: 18px;
        }


        /* =====================================================
           NUMERIC ONLY PAGINATION
           ===================================================== */

        .pagination-wrapper {
            margin-top: 30px;
            margin-bottom: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 40px;
            height: 40px;

            padding: 0 10px;

            border: 1px solid #ddd;
            border-radius: 6px;

            background: #ffffff;
            color: #007bff;

            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            box-sizing: border-box;
        }

        .pagination-number:hover {
            background: #007bff;
            color: #ffffff;
            border-color: #007bff;
        }

        .pagination-number.active {
            background: #007bff;
            color: #ffffff;
            border-color: #007bff;
            font-weight: bold;
        }

        .pagination-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 40px;
            height: 40px;

            color: #777;
            font-size: 16px;
        }


        @media (max-width: 900px) {

            .search-grid {
                grid-template-columns: 1fr;
            }

            .post-header {
                flex-direction: column;
            }

            .pagination-number {
                min-width: 35px;
                height: 35px;
                padding: 0 8px;
            }

        }

    </style>

</head>


<body>

<div class="container">


    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <div class="header">

        <div class="top-bar">

            <div class="buttons">

                <a
                    href="{{ route('language.change', 'en') }}"
                    class="btn btn-secondary">
                    English
                </a>

                <a
                    href="{{ route('language.change', 'hi') }}"
                    class="btn btn-secondary">
                    Hindi
                </a>

                <a
                    href="{{ route('dashboard') }}"
                    class="btn btn-dark">
                    📊 Dashboard
                </a>

                <a
                    href="{{ route('translations') }}"
                    class="btn btn-warning">
                    📝 Translations
                </a>

            </div>


            <a
                href="{{ route('posts.create') }}"
                class="btn btn-success">

                + Create Post

            </a>

        </div>


        <div class="locale">

            Current Language:

            <strong>
                {{ app()->getLocale() }}
            </strong>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
         ===================================================== --}}

    @if(session('success'))

        <div class="success">

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         SEARCH & FILTERS
         ===================================================== --}}

    <div class="search-box">

        <h3>
            🔎 Search & Advanced Filters
        </h3>


        <form
            method="GET"
            action="{{ route('posts.index') }}">


            <div class="search-grid">


                {{-- Search --}}
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search title, content or author...">


                {{-- Author --}}
                <input
                    type="text"
                    name="author"
                    value="{{ $author }}"
                    placeholder="Author...">


                {{-- Language --}}
                <select name="language">

                    <option
                        value="current"
                        {{ $language == 'current' ? 'selected' : '' }}>

                        Current Language

                    </option>


                    <option
                        value="all"
                        {{ $language == 'all' ? 'selected' : '' }}>

                        All Languages

                    </option>


                    <option
                        value="en"
                        {{ $language == 'en' ? 'selected' : '' }}>

                        English

                    </option>


                    <option
                        value="hi"
                        {{ $language == 'hi' ? 'selected' : '' }}>

                        Hindi

                    </option>

                </select>


                {{-- Status --}}
                <select name="status">

                    <option
                        value="all"
                        {{ $status == 'all' ? 'selected' : '' }}>

                        All Status

                    </option>


                    <option
                        value="complete"
                        {{ $status == 'complete' ? 'selected' : '' }}>

                        Complete

                    </option>


                    <option
                        value="partial"
                        {{ $status == 'partial' ? 'selected' : '' }}>

                        Partial

                    </option>


                    <option
                        value="missing"
                        {{ $status == 'missing' ? 'selected' : '' }}>

                        Missing

                    </option>

                </select>


                {{-- Sorting --}}
                <select name="sort">

                    <option
                        value="latest"
                        {{ $sort == 'latest' ? 'selected' : '' }}>

                        Newest

                    </option>


                    <option
                        value="oldest"
                        {{ $sort == 'oldest' ? 'selected' : '' }}>

                        Oldest

                    </option>


                    <option
                        value="id_asc"
                        {{ $sort == 'id_asc' ? 'selected' : '' }}>

                        ID Ascending

                    </option>


                    <option
                        value="id_desc"
                        {{ $sort == 'id_desc' ? 'selected' : '' }}>

                        ID Descending

                    </option>


                    <option
                        value="author_asc"
                        {{ $sort == 'author_asc' ? 'selected' : '' }}>

                        Author A-Z

                    </option>


                    <option
                        value="author_desc"
                        {{ $sort == 'author_desc' ? 'selected' : '' }}>

                        Author Z-A

                    </option>

                </select>


                {{-- Per Page --}}
                <select name="per_page">

                    @foreach([5, 10, 25, 50] as $size)

                        <option
                            value="{{ $size }}"
                            {{ $perPage == $size ? 'selected' : '' }}>

                            {{ $size }} per page

                        </option>

                    @endforeach

                </select>


                {{-- Filter --}}
                <button
                    type="submit"
                    class="btn btn-primary">

                    Filter

                </button>


                {{-- Reset --}}
                <a
                    href="{{ route('posts.index') }}"
                    class="btn btn-secondary">

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- =====================================================
         CSV EXPORT
         ===================================================== --}}

    <div class="export-box">

        <a
            href="{{ route('posts.export.csv') }}"
            class="btn btn-info">

            📄 Export All Posts CSV

        </a>


        <span style="margin-left:10px;">

            Export English and Hindi translations with status.

        </span>

    </div>


    {{-- =====================================================
         BULK ACTIONS
         ===================================================== --}}

    <form
        method="POST"
        action="{{ route('posts.bulk-delete') }}"
        id="bulkForm">

        @csrf


        <div class="bulk-box">

            <div class="bulk-bar">

                <div>

                    <label>

                        <input
                            type="checkbox"
                            id="selectAll"
                            class="checkbox">

                        Select All

                    </label>

                </div>


                <button
                    type="submit"
                    class="btn btn-danger"
                    onclick="return confirmBulkDelete()">

                    🗑️ Delete Selected

                </button>

            </div>

        </div>


        {{-- =================================================
             POSTS
             ================================================= --}}

        @forelse($posts as $post)


            @php

                $english = $post->translations
                    ->where('locale', 'en')
                    ->first();

                $hindi = $post->translations
                    ->where('locale', 'hi')
                    ->first();

                $hasEnglish = !is_null($english);

                $hasHindi = !is_null($hindi);


                if ($hasEnglish && $hasHindi) {

                    $translationStatus = 'Complete';

                    $statusClass = 'complete';

                } elseif ($hasEnglish || $hasHindi) {

                    $translationStatus = 'Partial';

                    $statusClass = 'partial';

                } else {

                    $translationStatus = 'Missing';

                    $statusClass = 'missing';

                }

            @endphp


            <div class="post-card">


                <div class="post-header">


                    <div>


                        <label>

                            <input
                                type="checkbox"
                                name="post_ids[]"
                                value="{{ $post->id }}"
                                class="post-checkbox checkbox">

                        </label>


                        <strong>

                            Post #{{ $post->id }}

                        </strong>


                        <h2 class="post-title">

                            {{ $post->title }}

                        </h2>

                    </div>


                    <div class="post-actions">


                        {{-- Edit --}}
                        <a
                            href="{{ route('translations.edit', $post) }}"
                            class="btn btn-primary">

                            ✏️ Edit

                        </a>


                        {{-- Duplicate --}}
                        <form
                            method="POST"
                            action="{{ route('posts.duplicate', $post) }}"
                            style="display:inline;">

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-info">

                                📋 Duplicate

                            </button>

                        </form>


                        {{-- Delete --}}
                        <form
                            method="POST"
                            action="{{ route('posts.destroy', $post) }}"
                            style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this post?');">

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger">

                                🗑️ Delete

                            </button>

                        </form>

                    </div>

                </div>


                {{-- Content --}}
                <p>

                    {{ $post->content }}

                </p>


                {{-- Author --}}
                @if($post->author)

                    <div class="author">

                        Author:
                        {{ $post->author }}

                    </div>

                @endif


                {{-- Translation Status --}}
                <div style="margin-top:12px;">


                    <span class="status {{ $statusClass }}">

                        {{ $translationStatus }}

                    </span>


                    @if($hasEnglish)

                        <span class="status complete">

                            English ✓

                        </span>

                    @else

                        <span class="status missing">

                            English ✗

                        </span>

                    @endif


                    @if($hasHindi)

                        <span class="status complete">

                            Hindi ✓

                        </span>

                    @else

                        <span class="status missing">

                            Hindi ✗

                        </span>

                    @endif

                </div>


                {{-- Displayed Language --}}
                <div class="locale">

                    Displayed Language:

                    <strong>

                        {{ app()->getLocale() }}

                    </strong>

                </div>

            </div>


        @empty


            <div class="post-card">

                <h3>

                    No Posts Found

                </h3>


                <p>

                    Try another search term or filter.

                </p>

            </div>


        @endforelse


    </form>


    {{-- =====================================================
         NUMBER-ONLY PAGINATION
         ===================================================== --}}

    @if($posts->hasPages())

        <div class="pagination-wrapper">

            <div class="pagination-numbers">


                @for(
                    $page = 1;
                    $page <= $posts->lastPage();
                    $page++
                )

                    <a
                        href="{{ $posts->url($page) }}"
                        class="pagination-number {{ $page == $posts->currentPage() ? 'active' : '' }}">

                        {{ $page }}

                    </a>

                @endfor


            </div>

        </div>

    @endif


</div>


<script>


    // =====================================================
    // SELECT ALL
    // =====================================================

    const selectAll =
        document.getElementById('selectAll');


    const checkboxes =
        document.querySelectorAll('.post-checkbox');


    if (selectAll) {

        selectAll.addEventListener(
            'change',
            function() {

                checkboxes.forEach(
                    function(checkbox) {

                        checkbox.checked =
                            selectAll.checked;

                    }
                );

            }
        );

    }


    // =====================================================
    // BULK DELETE CONFIRMATION
    // =====================================================

    function confirmBulkDelete() {


        const selected =
            document.querySelectorAll(
                '.post-checkbox:checked'
            );


        if (selected.length === 0) {

            alert(
                'Please select at least one post.'
            );

            return false;

        }


        return confirm(
            'Are you sure you want to delete ' +
            selected.length +
            ' selected post(s)?'
        );

    }

</script>


</body>

</html>
