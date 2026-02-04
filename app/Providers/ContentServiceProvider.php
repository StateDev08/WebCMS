<?php

namespace App\Providers;

use App\Services\BBCodeParser;
use App\Services\MarkdownParser;
use App\Services\BBCodeToMarkdownConverter;
use App\Services\MarkdownToBBCodeConverter;
use App\Services\ContentRenderer;
use Illuminate\Support\ServiceProvider;

class ContentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register als Singletons für bessere Performance
        $this->app->singleton(BBCodeParser::class);
        $this->app->singleton(MarkdownParser::class);
        $this->app->singleton(BBCodeToMarkdownConverter::class);
        $this->app->singleton(MarkdownToBBCodeConverter::class);
        $this->app->singleton(ContentRenderer::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
