<!DOCTYPE html>
<html>
<head>
    <title>All Posts</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 800px;
            margin: 40px auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            color: white;
        }

        .btn-secondary { background: #6c757d; }
        .btn-success { background: #28a745; }

        .btn:hover {
            opacity: 0.8;
        }

        .post-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.05);
        }

        h2 {
            margin-top: 0;
        }

        .empty {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .locale {
            font-size: 12px;
            color: gray;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top-bar">
        <div>
            <a href="/lang/en" class="btn btn-secondary">English</a>
            <a href="/lang/hi" class="btn btn-secondary">Hindi</a>
        </div>

        <a href="/create" class="btn btn-success">+ Create Post</a>
    </div>

    <div class="locale">
        Current Language: {{ app()->getLocale() }}
    </div>

    @forelse($posts as $post)
        <div class="post-card">
            <h2>{{ $post->title }}</h2>
            <p>{{ $post->content }}</p>
        </div>
    @empty
        <div class="empty">
            <h3>No Posts Available</h3>
        </div>
    @endforelse

</div>

</body>
</html>
