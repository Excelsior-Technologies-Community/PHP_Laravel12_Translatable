<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Support\Facades\DB;

class TranslationStudioService
{
    /**
     * Common English to Hindi Translation Dictionary & Rule-based Transliteration.
     */
    private array $dictionary = [
        'welcome' => 'स्वागत हे',
        'hello' => 'नमस्ते',
        'laravel' => 'लारवेल',
        'framework' => 'फ्रेमवर्क',
        'post' => 'पोस्ट',
        'title' => 'शीर्षक',
        'content' => 'सामग्री',
        'guide' => 'मार्गदर्शिका',
        'tutorial' => 'ट्यूटोरियल',
        'news' => 'समाचार',
        'update' => 'अपडेट',
        'technology' => 'प्रौद्योगिकी',
        'business' => 'व्यापार',
        'education' => 'शिक्षा',
        'release' => 'रिलीज़',
        'features' => 'विशेषताएं',
        'documentation' => 'दस्तावेज़ीकरण',
        'application' => 'आवेदन',
    ];

    /**
     * Auto translate English text to target locale (Hindi).
     */
    public function autoTranslate(string $text, string $targetLocale = 'hi'): string
    {
        if (trim($text) === '') {
            return '';
        }

        if ($targetLocale === 'en') {
            return $text;
        }

        $words = preg_split('/(\s+|[^\w\s]+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $translatedWords = [];

        foreach ($words as $word) {
            $cleanWord = strtolower(trim($word));

            if (isset($this->dictionary[$cleanWord])) {
                $translatedWords[] = $this->dictionary[$cleanWord];
            } else {
                $translatedWords[] = $word;
            }
        }

        $result = implode('', $translatedWords);

        if ($result === $text && $targetLocale === 'hi') {
            return '[हिंदी अनुवाद] ' . $text;
        }

        return $result;
    }

    /**
     * Get completeness analytics and coverage heatmap metrics.
     */
    public function getAnalyticsData(): array
    {
        $totalPosts = Post::count();

        $enCount = PostTranslation::where('locale', 'en')->count();
        $hiCount = PostTranslation::where('locale', 'hi')->count();

        $fullyTranslatedCount = Post::whereHas('translations', function ($q) {
            $q->where('locale', 'en');
        })->whereHas('translations', function ($q) {
            $q->where('locale', 'hi');
        })->count();

        $partiallyTranslatedCount = Post::where(function ($query) {
            $query->whereHas('translations', function ($q) {
                $q->where('locale', 'en');
            })->whereDoesntHave('translations', function ($q) {
                $q->where('locale', 'hi');
            });
        })->orWhere(function ($query) {
            $query->whereHas('translations', function ($q) {
                $q->where('locale', 'hi');
            })->whereDoesntHave('translations', function ($q) {
                $q->where('locale', 'en');
            });
        })->count();

        $missingCount = Post::whereDoesntHave('translations', function ($q) {
            $q->where('locale', 'en');
        })->whereDoesntHave('translations', function ($q) {
            $q->where('locale', 'hi');
        })->count();

        $enPercentage = $totalPosts > 0 ? round(($enCount / $totalPosts) * 100, 1) : 0;
        $hiPercentage = $totalPosts > 0 ? round(($hiCount / $totalPosts) * 100, 1) : 0;
        $fullPercentage = $totalPosts > 0 ? round(($fullyTranslatedCount / $totalPosts) * 100, 1) : 0;

        // List posts with missing translations
        $missingPosts = Post::with('translations')
            ->where(function ($q) {
                $q->whereDoesntHave('translations', function ($sub) {
                    $sub->where('locale', 'en');
                })->orWhereDoesntHave('translations', function ($sub) {
                    $sub->where('locale', 'hi');
                });
            })
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($post) {
                $hasEn = $post->translations->where('locale', 'en')->first() !== null;
                $hasHi = $post->translations->where('locale', 'hi')->first() !== null;
                return [
                    'id' => $post->id,
                    'author' => $post->author ?: 'Anonymous',
                    'has_en' => $hasEn,
                    'has_hi' => $hasHi,
                    'missing_locales' => array_values(array_diff(['en', 'hi'], array_filter([$hasEn ? 'en' : null, $hasHi ? 'hi' : null]))),
                ];
            })->toArray();

        return [
            'summary' => [
                'total_posts' => $totalPosts,
                'en_count' => $enCount,
                'hi_count' => $hiCount,
                'en_percentage' => $enPercentage,
                'hi_percentage' => $hiPercentage,
                'full_percentage' => $fullPercentage,
                'fully_translated' => $fullyTranslatedCount,
                'partially_translated' => $partiallyTranslatedCount,
                'missing_count' => $missingCount,
            ],
            'chart_locales' => ['English (en)', 'Hindi (hi)'],
            'chart_coverage' => [$enPercentage, $hiPercentage],
            'chart_status_labels' => ['Fully Translated', 'Partially Translated', 'Missing Both'],
            'chart_status_data' => [$fullyTranslatedCount, $partiallyTranslatedCount, $missingCount],
            'missing_posts' => $missingPosts,
        ];
    }

    /**
     * Import bulk JSON multi-language posts.
     */
    public function importBulkJson(array $records): int
    {
        $importedCount = 0;

        DB::transaction(function () use ($records, &$importedCount) {
            foreach ($records as $item) {
                $post = Post::create([
                    'author' => $item['author'] ?? 'Importer',
                ]);

                if (!empty($item['title_en'])) {
                    $post->translateOrNew('en')->fill([
                        'title' => $item['title_en'],
                        'content' => $item['content_en'] ?? '',
                    ]);
                }

                if (!empty($item['title_hi'])) {
                    $post->translateOrNew('hi')->fill([
                        'title' => $item['title_hi'],
                        'content' => $item['content_hi'] ?? '',
                    ]);
                }

                $post->save();
                $importedCount++;
            }
        });

        return $importedCount;
    }

    /**
     * Import bulk CSV multi-language posts.
     */
    public function importBulkCsv(string $csvContent): int
    {
        $lines = explode("\n", trim($csvContent));
        if (count($lines) < 2) return 0;

        $header = str_getcsv(array_shift($lines));
        $importedCount = 0;

        DB::transaction(function () use ($lines, &$importedCount) {
            foreach ($lines as $line) {
                if (trim($line) === '') continue;

                $row = str_getcsv($line);
                $author = $row[0] ?? 'Importer';
                $titleEn = $row[1] ?? '';
                $contentEn = $row[2] ?? '';
                $titleHi = $row[3] ?? '';
                $contentHi = $row[4] ?? '';

                $post = Post::create(['author' => $author]);

                if (!empty($titleEn)) {
                    $post->translateOrNew('en')->fill([
                        'title' => $titleEn,
                        'content' => $contentEn,
                    ]);
                }

                if (!empty($titleHi)) {
                    $post->translateOrNew('hi')->fill([
                        'title' => $titleHi,
                        'content' => $contentHi,
                    ]);
                }

                $post->save();
                $importedCount++;
            }
        });

        return $importedCount;
    }

    /**
     * Export all posts as JSON multi-language structure.
     */
    public function exportBulkJson(): array
    {
        return Post::with('translations')->latest()->get()->map(function ($post) {
            $en = $post->translations->where('locale', 'en')->first();
            $hi = $post->translations->where('locale', 'hi')->first();

            return [
                'id' => $post->id,
                'author' => $post->author,
                'created_at' => $post->created_at?->toIso8601String(),
                'translations' => [
                    'en' => [
                        'title' => $en?->title ?? '',
                        'content' => $en?->content ?? '',
                    ],
                    'hi' => [
                        'title' => $hi?->title ?? '',
                        'content' => $hi?->content ?? '',
                    ],
                ],
            ];
        })->toArray();
    }
}
