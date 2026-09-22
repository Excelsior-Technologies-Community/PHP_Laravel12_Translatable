<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display posts with multilingual search and language filtering.
     */
    public function index(Request $request)
    {
        $currentLocale = app()->getLocale();

        $search = trim($request->input('search', ''));

        /*
        |--------------------------------------------------------------------------
        | Language Filter
        |--------------------------------------------------------------------------
        |
        | current = current application language
        | all     = all languages
        | en      = English
        | hi      = Hindi
        |
        */

        $language = $request->input('language', 'current');

        /*
        |--------------------------------------------------------------------------
        | Set Display Language
        |--------------------------------------------------------------------------
        */

        if (in_array($language, ['en', 'hi'], true)) {
            App::setLocale($language);
        } elseif ($language === 'current') {
            App::setLocale($currentLocale);
        }

        /*
        |--------------------------------------------------------------------------
        | Determine Language Used For Database Search
        |--------------------------------------------------------------------------
        */

        $searchLocale = null;

        if ($language === 'en' || $language === 'hi') {
            $searchLocale = $language;
        } elseif ($language === 'current') {
            $searchLocale = App::getLocale();
        }

        $query = Post::query();

        /*
        |--------------------------------------------------------------------------
        | Language Filtering
        |--------------------------------------------------------------------------
        */

        if ($searchLocale !== null) {

            $query->whereHas('translations', function ($q) use ($searchLocale) {
                $q->where('locale', $searchLocale);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Multilingual Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search, $searchLocale) {

                /*
                |--------------------------------------------------------------------------
                | Search Author
                |--------------------------------------------------------------------------
                */

                $q->where('author', 'LIKE', '%' . $search . '%')

                    /*
                    |--------------------------------------------------------------------------
                    | Search Translation
                    |--------------------------------------------------------------------------
                    */

                    ->orWhereHas('translations', function ($translationQuery) use (
                        $search,
                        $searchLocale
                    ) {

                        $translationQuery->where(function ($translationSearch) use ($search) {

                            $translationSearch
                                ->where(
                                    'title',
                                    'LIKE',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'content',
                                    'LIKE',
                                    '%' . $search . '%'
                                );
                        });

                        /*
                        |--------------------------------------------------------------------------
                        | Search Only Selected Language
                        |--------------------------------------------------------------------------
                        */

                        if ($searchLocale !== null) {

                            $translationQuery->where(
                                'locale',
                                $searchLocale
                            );
                        }
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Load Posts
        |--------------------------------------------------------------------------
        */

        $posts = $query
            ->with('translations')
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('index', compact(
            'posts',
            'search',
            'language'
        ));
    }


    /**
     * Show create post form.
     */
    public function create()
    {
        return view('create');
    }


    /**
     * Store a new post with translations.
     */
    public function store(Request $request)
    {
        $request->validate([
            'author' => 'nullable|string|max:255',

            'title_en' => [
                'nullable',
                'string',
                'max:255',
                'required_without:title_hi',
            ],

            'title_hi' => [
                'nullable',
                'string',
                'max:255',
                'required_without:title_en',
            ],

            'content_en' => 'nullable|string',

            'content_hi' => 'nullable|string',
        ]);

        $data = [
            'author' => $request->author,
        ];

        /*
        |--------------------------------------------------------------------------
        | English Translation
        |--------------------------------------------------------------------------
        */

        if ($request->filled('title_en')) {

            $data['en'] = [
                'title' => $request->title_en,
                'content' => $request->content_en,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hindi Translation
        |--------------------------------------------------------------------------
        */

        if ($request->filled('title_hi')) {

            $data['hi'] = [
                'title' => $request->title_hi,
                'content' => $request->content_hi,
            ];
        }

        Post::create($data);

        return redirect('/')
            ->with(
                'success',
                'Post created successfully with translations.'
            );
    }


    /**
     * Translation management dashboard.
     */
    public function dashboard()
    {
        $totalPosts = Post::count();

        $englishTranslations = DB::table('post_translations')
            ->where('locale', 'en')
            ->count();

        $hindiTranslations = DB::table('post_translations')
            ->where('locale', 'hi')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Fully Translated Posts
        |--------------------------------------------------------------------------
        */

        $fullyTranslated = DB::table('posts')
            ->whereIn('id', function ($query) {
                $query->select('post_id')
                    ->from('post_translations')
                    ->where('locale', 'en');
            })
            ->whereIn('id', function ($query) {
                $query->select('post_id')
                    ->from('post_translations')
                    ->where('locale', 'hi');
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Partially Translated Posts
        |--------------------------------------------------------------------------
        */

        $partiallyTranslated = DB::table('posts')
            ->where(function ($query) {

                $query
                    ->whereIn('id', function ($subQuery) {
                        $subQuery->select('post_id')
                            ->from('post_translations')
                            ->where('locale', 'en');
                    })
                    ->whereNotIn('id', function ($subQuery) {
                        $subQuery->select('post_id')
                            ->from('post_translations')
                            ->where('locale', 'hi');
                    });

            })
            ->orWhere(function ($query) {

                $query
                    ->whereIn('id', function ($subQuery) {
                        $subQuery->select('post_id')
                            ->from('post_translations')
                            ->where('locale', 'hi');
                    })
                    ->whereNotIn('id', function ($subQuery) {
                        $subQuery->select('post_id')
                            ->from('post_translations')
                            ->where('locale', 'en');
                    });

            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Missing Translations
        |--------------------------------------------------------------------------
        */

        $missingTranslations = max(
            0,
            ($totalPosts * 2)
            - ($englishTranslations + $hindiTranslations)
        );

        $posts = Post::with('translations')
            ->latest()
            ->paginate(10);

        return view('dashboard', compact(
            'totalPosts',
            'englishTranslations',
            'hindiTranslations',
            'fullyTranslated',
            'partiallyTranslated',
            'missingTranslations',
            'posts'
        ));
    }


    /**
     * Translation completeness manager.
     */
    public function translations()
    {
        $posts = Post::with('translations')
            ->latest()
            ->paginate(10);

        return view('translations', compact('posts'));
    }


    /**
     * Show edit translation form.
     */
    public function editTranslation(Post $post)
    {
        $post->load('translations');

        return view(
            'edit-translation',
            compact('post')
        );
    }


    /**
     * Update English and Hindi translations.
     */
    public function updateTranslation(
        Request $request,
        Post $post
    ) {
        $request->validate([
            'title_en' => 'nullable|string|max:255',
            'content_en' => 'nullable|string',

            'title_hi' => 'nullable|string|max:255',
            'content_hi' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | English Translation
        |--------------------------------------------------------------------------
        */

        if ($request->filled('title_en')) {

            $translation = $post->translateOrNew('en');

            $translation->title = $request->title_en;
            $translation->content = $request->content_en;
        }

        /*
        |--------------------------------------------------------------------------
        | Hindi Translation
        |--------------------------------------------------------------------------
        */

        if ($request->filled('title_hi')) {

            $translation = $post->translateOrNew('hi');

            $translation->title = $request->title_hi;
            $translation->content = $request->content_hi;
        }

        $post->save();

        return redirect()
            ->route('translations')
            ->with(
                'success',
                'Translations updated successfully.'
            );
    }


    /**
     * Change application language.
     */
    public function changeLang($locale)
    {
        if (in_array($locale, ['en', 'hi'], true)) {

            session()->put(
                'locale',
                $locale
            );
        }

        return redirect()->back();
    }
}