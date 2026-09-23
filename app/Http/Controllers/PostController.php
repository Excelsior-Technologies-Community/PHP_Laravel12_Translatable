<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostController extends Controller
{
    /**
     * Display posts with search, filters, sorting and pagination.
     */
    public function index(Request $request)
    {
        $currentLocale = app()->getLocale();

        $search = trim($request->input('search', ''));
        $author = trim($request->input('author', ''));

        $language = $request->input('language', 'current');

        $status = $request->input('status', 'all');

        $sort = $request->input('sort', 'latest');

        $perPage = (int) $request->input('per_page', 5);

        /*
        |--------------------------------------------------------------------------
        | Allowed values
        |--------------------------------------------------------------------------
        */

        if (!in_array($language, ['current', 'all', 'en', 'hi'], true)) {
            $language = 'current';
        }

        if (!in_array($status, ['all', 'complete', 'partial', 'missing'], true)) {
            $status = 'all';
        }

        if (!in_array($sort, ['latest', 'oldest', 'id_asc', 'id_desc', 'author_asc', 'author_desc'], true)) {
            $sort = 'latest';
        }

        if (!in_array($perPage, [5, 10, 25, 50], true)) {
            $perPage = 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Set display language
        |--------------------------------------------------------------------------
        */

        if (in_array($language, ['en', 'hi'], true)) {
            App::setLocale($language);
        } elseif ($language === 'current') {
            App::setLocale($currentLocale);
        }

        /*
        |--------------------------------------------------------------------------
        | Determine search language
        |--------------------------------------------------------------------------
        */

        $searchLocale = null;

        if (in_array($language, ['en', 'hi'], true)) {
            $searchLocale = $language;
        } elseif ($language === 'current') {
            $searchLocale = App::getLocale();
        }

        $query = Post::query();

        /*
        |--------------------------------------------------------------------------
        | Author Filter
        |--------------------------------------------------------------------------
        */

        if ($author !== '') {
            $query->where(
                'author',
                'LIKE',
                '%' . $author . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Language Filter
        |--------------------------------------------------------------------------
        */

        if ($searchLocale !== null) {
            $query->whereHas(
                'translations',
                function ($q) use ($searchLocale) {
                    $q->where('locale', $searchLocale);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Translation Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status === 'complete') {

            $query
                ->whereHas('translations', function ($q) {
                    $q->where('locale', 'en');
                })
                ->whereHas('translations', function ($q) {
                    $q->where('locale', 'hi');
                });

        } elseif ($status === 'partial') {

            $query->where(function ($q) {

                $q->where(function ($subQuery) {

                    $subQuery
                        ->whereHas('translations', function ($translation) {
                            $translation->where('locale', 'en');
                        })
                        ->whereDoesntHave('translations', function ($translation) {
                            $translation->where('locale', 'hi');
                        });

                })->orWhere(function ($subQuery) {

                    $subQuery
                        ->whereHas('translations', function ($translation) {
                            $translation->where('locale', 'hi');
                        })
                        ->whereDoesntHave('translations', function ($translation) {
                            $translation->where('locale', 'en');
                        });

                });

            });

        } elseif ($status === 'missing') {

            $query
                ->whereDoesntHave('translations', function ($translation) {
                    $translation->where('locale', 'en');
                })
                ->whereDoesntHave('translations', function ($translation) {
                    $translation->where('locale', 'hi');
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Multilingual Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search, $searchLocale) {

                $q->where(
                    'author',
                    'LIKE',
                    '%' . $search . '%'
                );

                $q->orWhereHas(
                    'translations',
                    function ($translationQuery) use ($search, $searchLocale) {

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

                        if ($searchLocale !== null) {

                            $translationQuery->where(
                                'locale',
                                $searchLocale
                            );
                        }
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($sort) {

            case 'oldest':
                $query->oldest();
                break;

            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;

            case 'id_desc':
                $query->orderBy('id', 'desc');
                break;

            case 'author_asc':
                $query->orderBy('author', 'asc');
                break;

            case 'author_desc':
                $query->orderBy('author', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Load Posts
        |--------------------------------------------------------------------------
        */

        $posts = $query
            ->with('translations')
            ->paginate($perPage)
            ->withQueryString();

        return view('index', compact(
            'posts',
            'search',
            'author',
            'language',
            'status',
            'sort',
            'perPage'
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

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                'Post created successfully with translations.'
            );
    }


    /**
     * Translation dashboard.
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
     * Show translation edit form.
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
     * Update author and translations.
     */
    public function updateTranslation(
        Request $request,
        Post $post
    ) {
        $request->validate([
            'author' => 'nullable|string|max:255',

            'title_en' => 'nullable|string|max:255',
            'content_en' => 'nullable|string',

            'title_hi' => 'nullable|string|max:255',
            'content_hi' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Author
        |--------------------------------------------------------------------------
        */

        $post->author = $request->author;

        /*
        |--------------------------------------------------------------------------
        | English Translation
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('title_en')
            || $request->filled('content_en')
        ) {

            $translation = $post->translateOrNew('en');

            $translation->title = $request->title_en;
            $translation->content = $request->content_en;
        }

        /*
        |--------------------------------------------------------------------------
        | Hindi Translation
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('title_hi')
            || $request->filled('content_hi')
        ) {

            $translation = $post->translateOrNew('hi');

            $translation->title = $request->title_hi;
            $translation->content = $request->content_hi;
        }

        $post->save();

        return redirect()
            ->route('translations')
            ->with(
                'success',
                'Post and translations updated successfully.'
            );
    }


    /**
     * Delete a post.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                'Post deleted successfully.'
            );
    }


    /**
     * Bulk delete selected posts.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'post_ids' => 'required|array|min:1',
            'post_ids.*' => 'integer|exists:posts,id',
        ]);

        Post::whereIn(
            'id',
            $request->post_ids
        )->delete();

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                count($request->post_ids)
                . ' post(s) deleted successfully.'
            );
    }


    /**
     * Duplicate a post with all translations.
     */
    public function duplicate(Post $post)
    {
        $post->load('translations');

        $newPost = Post::create([
            'author' => $post->author,
        ]);

        foreach ($post->translations as $translation) {

            $newTranslation = $newPost->translateOrNew(
                $translation->locale
            );

            $newTranslation->title = $translation->title;
            $newTranslation->content = $translation->content;
        }

        $newPost->save();

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                'Post duplicated successfully with all translations.'
            );
    }


    /**
     * Export posts as CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $posts = Post::with('translations')
            ->latest()
            ->get();

        $filename = 'multilingual-posts-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(
            function () use ($posts) {

                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'ID',
                    'Author',
                    'English Title',
                    'English Content',
                    'Hindi Title',
                    'Hindi Content',
                    'Translation Status',
                ]);

                foreach ($posts as $post) {

                    $english = $post->translations
                        ->where('locale', 'en')
                        ->first();

                    $hindi = $post->translations
                        ->where('locale', 'hi')
                        ->first();

                    $hasEnglish = !is_null($english);
                    $hasHindi = !is_null($hindi);

                    if ($hasEnglish && $hasHindi) {
                        $status = 'Complete';
                    } elseif ($hasEnglish || $hasHindi) {
                        $status = 'Partial';
                    } else {
                        $status = 'Missing';
                    }

                    fputcsv($handle, [
                        $post->id,
                        $post->author,
                        $english?->title ?? '',
                        $english?->content ?? '',
                        $hindi?->title ?? '',
                        $hindi?->content ?? '',
                        $status,
                    ]);
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' => 'text/csv',
            ]
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