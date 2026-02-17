<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Display posts only for the currently selected locale
    public function index()
    {
        $posts = Post::translatedIn(app()->getLocale())->get();

        return view('index', compact('posts'));
    }

    // Show the form to create a new post
    public function create()
    {
        return view('create');
    }

    // Store a new post with at least one language translation
    public function store(Request $request)
    {
        // Validate that at least one language title is provided
        $request->validate([
            'title_en' => 'required_without:title_hi',
            'title_hi' => 'required_without:title_en',
        ]);

        // Prepare base post data
        $data = [
            'author' => $request->author,
        ];

        // Add English translation if provided
        if ($request->filled('title_en')) {
            $data['en'] = [
                'title' => $request->title_en,
                'content' => $request->content_en,
            ];
        }

        // Add Hindi translation if provided
        if ($request->filled('title_hi')) {
            $data['hi'] = [
                'title' => $request->title_hi,
                'content' => $request->content_hi,
            ];
        }

        // Create the post with translations
        Post::create($data);

        return redirect('/');
    }

    // Change the application language using session
    public function changeLang($locale)
    {
        if (in_array($locale, ['en', 'hi'])) {
            session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
