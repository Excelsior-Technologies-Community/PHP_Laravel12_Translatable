<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .lang-section {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error-box {
            background: #ffe5e5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .error-box li {
            color: red;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Create Post</h2>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/store">
        @csrf

        <label>Author</label>
        <input type="text" name="author" value="{{ old('author') }}" placeholder="Enter author name">

        <div class="lang-section">
            <h3>English</h3>

            <label>Title</label>
            <input type="text" name="title_en" value="{{ old('title_en') }}">

            <label>Content</label>
            <textarea name="content_en">{{ old('content_en') }}</textarea>
        </div>

        <div class="lang-section">
            <h3>Hindi</h3>

            <label>Title</label>
            <input type="text" name="title_hi" value="{{ old('title_hi') }}">

            <label>Content</label>
            <textarea name="content_hi">{{ old('content_hi') }}</textarea>
        </div>

        <button type="submit">Save Post</button>
    </form>

    <div class="back-link">
        <a href="/">⬅ Back to Posts</a>
    </div>
</div>

</body>
</html>
