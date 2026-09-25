<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Services\TranslationStudioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslatableAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_render_auto_translator_page()
    {
        $response = $this->get(route('translator.index'));

        $response->assertStatus(200);
        $response->assertSee('Auto-Translation Studio', false);
    }

    /** @test */
    public function it_can_auto_translate_text_via_api()
    {
        $response = $this->postJson(route('translator.auto'), [
            'text' => 'Welcome to Laravel framework tutorial',
            'target' => 'hi',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'target' => 'hi',
        ]);
    }

    /** @test */
    public function it_can_render_completeness_analytics_page()
    {
        $post = Post::create(['author' => 'Test Author']);
        $post->translateOrNew('en')->fill(['title' => 'English Title'])->save();

        $response = $this->get(route('analytics.index'));

        $response->assertStatus(200);
        $response->assertSee('Multilingual Completeness Analytics', false);
    }

    /** @test */
    public function it_returns_json_analytics_data()
    {
        $response = $this->get(route('analytics.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'summary' => [
                'total_posts',
                'en_count',
                'hi_count',
                'en_percentage',
                'hi_percentage',
                'full_percentage',
            ],
            'chart_locales',
            'chart_coverage',
            'chart_status_labels',
            'chart_status_data',
            'missing_posts',
        ]);
    }

    /** @test */
    public function it_can_render_bulk_manage_page()
    {
        $response = $this->get(route('bulk.manage'));

        $response->assertStatus(200);
        $response->assertSee('Bulk Import & Export Studio', false);
    }

    /** @test */
    public function it_can_import_bulk_json_posts()
    {
        $jsonPayload = json_encode([
            [
                'author' => 'Bulk Author',
                'title_en' => 'Bulk Title English',
                'content_en' => 'Bulk Content English',
                'title_hi' => 'बल्क शीर्षक हिंदी',
                'content_hi' => 'बल्क सामग्री हिंदी',
            ]
        ]);

        $response = $this->post(route('bulk.import.json'), [
            'json_text' => $jsonPayload,
        ]);

        $response->assertRedirect(route('bulk.manage'));
        $this->assertDatabaseHas('posts', ['author' => 'Bulk Author']);
        $this->assertDatabaseHas('post_translations', ['title' => 'Bulk Title English']);
        $this->assertDatabaseHas('post_translations', ['title' => 'बल्क शीर्षक हिंदी']);
    }

    /** @test */
    public function it_can_export_bulk_json_package()
    {
        $post = Post::create(['author' => 'Export Author']);
        $post->translateOrNew('en')->fill(['title' => 'Export EN'])->save();

        $response = $this->get(route('bulk.export.json'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }
}
