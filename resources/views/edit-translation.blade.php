<!DOCTYPE html>
<html>

<head>

    <title>Manage Post Translation</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 800px;
            max-width: 95%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .language-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .english {
            border-left: 5px solid #007bff;
        }

        .hindi {
            border-left: 5px solid #28a745;
        }

        label {
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .error-box {
            background: #ffe5e5;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-box li {
            color: red;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            text-decoration: none;
            color: #007bff;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>
        📝 Manage Translation
        <br>
        <small>Post #{{ $post->id }}</small>
    </h1>


    @if ($errors->any())

        <div class="error-box">

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('translations.update', $post) }}"
    >

        @csrf

        @method('PUT')


        {{-- English --}}

        <div class="language-box english">

            <h2>🇬🇧 English Translation</h2>

            <label>Title</label>

            <input
                type="text"
                name="title_en"
                value="{{ old('title_en', optional($post->translate('en'))->title) }}"
                placeholder="Enter English title"
            >


            <label>Content</label>

            <textarea
                name="content_en"
                placeholder="Enter English content"
            >{{ old('content_en', optional($post->translate('en'))->content) }}</textarea>

        </div>


        {{-- Hindi --}}

        <div class="language-box hindi">

            <h2>🇮🇳 Hindi Translation</h2>

            <label>Title</label>

            <input
                type="text"
                name="title_hi"
                value="{{ old('title_hi', optional($post->translate('hi'))->title) }}"
                placeholder="हिंदी शीर्षक दर्ज करें"
            >


            <label>Content</label>

            <textarea
                name="content_hi"
                placeholder="हिंदी सामग्री दर्ज करें"
            >{{ old('content_hi', optional($post->translate('hi'))->content) }}</textarea>

        </div>


        <button type="submit">
            💾 Save Translations
        </button>

    </form>


    <div class="back">

        <a href="{{ route('translations') }}">
            ⬅ Back to Translation Manager
        </a>

    </div>

</div>

</body>

</html>