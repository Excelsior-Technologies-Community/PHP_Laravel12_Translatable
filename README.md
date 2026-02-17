#  PHP_Laravel12_Translatable

![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Package](https://img.shields.io/badge/Package-astrotomic%2Flaravel--translatable-green)

---

#  Overview

This project demonstrates how to build a **production-ready bilingual CRUD system** in **Laravel 12** using the **Astrotomic Laravel Translatable** package.

It supports:

* English & Hindi language translations
* Separate translation table structure
* Language switching with middleware
* Validation rules
* Clean UI with Blade templates
* Proper fallback locale handling

This project is ideal for learning multilingual database design in Laravel.

---

#  Features

*  Laravel 12 setup
*  Multi-language support (English & Hindi)
*  Translation table architecture
*  Session-based language switching
*  Validation (at least one language required)
*  Fallback language support
*  Clean and minimal UI
*  Production-ready folder structure

---

#  Folder Structure

```
Laravel12_Translatable/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── PostController.php
│   │   └── Middleware/
│   │       └── SetLocale.php
│   ├── Models/
│   │   ├── Post.php
│   │   └── PostTranslation.php
│
├── config/
│   └── translatable.php
│
├── database/
│   └── migrations/
│       ├── create_posts_table.php
│       └── create_post_translations_table.php
│
├── resources/
│   └── views/
│       ├── create.blade.php
│       └── index.blade.php
│
├── routes/
│   └── web.php
│
└── README.md
```

---

## 1. Requirements

* PHP 8.2+
* Composer
* MySQL
* XAMPP / Laragon / Local server

---

## 2. Create Laravel 12 Project

```bash
composer create-project laravel/laravel Laravel12_Translatable
cd Laravel12_Translatable
```

---

## 3. Configure Database

Open `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=translate
DB_USERNAME=root
DB_PASSWORD=

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

Then run:

```bash
php artisan migrate
```

---

## 4. Install Translatable Package

```bash
composer require astrotomic/laravel-translatable
```

Publish config:

```bash
php artisan vendor:publish --tag=translatable
```

---

## 5. Configure Languages

Open:

```
config/translatable.php
```

```php
<?php

return [

    'locales' => [
        'en',
        'hi',
    ],

    'locale_separator' => '-',

    'locale' => null,

    'use_fallback' => true,

    'use_property_fallback' => true,

    'fallback_locale' => 'en',

    'translation_model_namespace' => null,

    'translation_suffix' => 'Translation',

    'locale_key' => 'locale',

    'to_array_always_loads_translations' => true,

    'rule_factory' => [
        'format' => \Astrotomic\Translatable\Validation\RuleFactory::FORMAT_ARRAY,
        'prefix' => '%',
        'suffix' => '%',
    ],

    'translations_wrapper' => null,

];
```

Clear cache:

```bash
php artisan optimize:clear
```

---

## 6. Create Models & Migrations

```bash
php artisan make:model Post -m

php artisan make:model PostTranslation -m
```

---

## 7. Migrations Code

### create_posts_table.php

```php
public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('author')->nullable();
        $table->timestamps();
    });
}
```

### create_post_translations_table.php

⚠ Must run after posts migration.

```php
public function up(): void
{
    Schema::create('post_translations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('post_id')->constrained()->onDelete('cascade');
        $table->string('locale')->index();
        $table->string('title');
        $table->text('content')->nullable();
        $table->unique(['post_id', 'locale']);
    });
}
```

Run:

```bash
php artisan migrate
```

---

## 8. Models Setup

### app/Models/Post.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Post extends Model
{
    use Translatable;

    protected $fillable = ['author'];

    public $translatedAttributes = ['title', 'content'];
}
```

### app/Models/PostTranslation.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'content'];
}
```

---

## 9. Create Middleware (Laravel 12 Method)

```bash
php artisan make:middleware SetLocale
```

### app/Http/Middleware/SetLocale.php

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }

        return $next($request);
    }
}
```

### Register Middleware (Laravel 12 Way)

Open:

```
bootstrap/app.php
```

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->appendToGroup('web', [
        \App\Http\Middleware\SetLocale::class,
    ]);
})
```

---

## 10. Create Controller

```bash
php artisan make:controller PostController
```

### app/Http/Controllers/PostController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::translatedIn(app()->getLocale())->get();
        return view('index', compact('posts'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required_without:title_hi',
            'title_hi' => 'required_without:title_en',
        ]);

        $data = [
            'author' => $request->author,
        ];

        if ($request->filled('title_en')) {
            $data['en'] = [
                'title' => $request->title_en,
                'content' => $request->content_en,
            ];
        }

        if ($request->filled('title_hi')) {
            $data['hi'] = [
                'title' => $request->title_hi,
                'content' => $request->content_hi,
            ];
        }

        Post::create($data);

        return redirect('/');
    }

    public function changeLang($locale)
    {
        if (in_array($locale, ['en', 'hi'])) {
            session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
```

---

## 11. Routes

### routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'index']);
Route::get('/create', [PostController::class, 'create']);
Route::post('/store', [PostController::class, 'store']);
Route::get('/lang/{locale}', [PostController::class, 'changeLang']);
```

---

## 12. Create Blade Files

Create:

```
resources/views/create.blade.php
resources/views/index.blade.php
```

### resources/views/create.blade.php

```html
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
```

---

### resources/views/index.blade.php

```html
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
```

---

## 13. Run Project

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000/create
```
<img width="597" height="854" alt="Screenshot 2026-02-17 170614" src="https://github.com/user-attachments/assets/8a8132d7-c496-4c78-81f6-870ad90c6520" />



Switch language:

```
http://127.0.0.1:8000/lang/en
```
<img width="859" height="400" alt="Screenshot 2026-02-17 170701" src="https://github.com/user-attachments/assets/84fa9a84-78ba-4068-9eeb-6a6c51c1b877" />

```
http://127.0.0.1:8000/lang/hi
```
<img width="858" height="393" alt="Screenshot 2026-02-17 170711" src="https://github.com/user-attachments/assets/09120c08-6266-41c6-bb6b-ce39894f3ec4" />

---



