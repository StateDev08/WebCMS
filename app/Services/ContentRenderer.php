<?php

namespace App\Services;

use App\Models\ForumPost;
use Illuminate\Support\Facades\Cache;

class ContentRenderer
{
    public function __construct(
        protected BBCodeParser $bbcodeParser,
        protected MarkdownParser $markdownParser,
        protected BBCodeToMarkdownConverter $bbcodeToMarkdown,
        protected MarkdownToBBCodeConverter $markdownToBBCode
    ) {}

    /**
     * Rendert Content in HTML basierend auf dem Format
     */
    public function render(ForumPost $post): string
    {
        // Nutze gecachtes HTML wenn verfügbar
        if ($post->content_html) {
            return $post->content_html;
        }

        return $this->renderAndCache($post);
    }

    /**
     * Rendert und cached Content
     */
    public function renderAndCache(ForumPost $post): string
    {
        $html = match ($post->content_format) {
            'bbcode' => $this->bbcodeParser->toHtml($post->content_original),
            'markdown' => $this->markdownParser->toHtml($post->content_original),
            default => htmlspecialchars($post->content_original),
        };

        $post->update(['content_html' => $html]);
        
        return $html;
    }

    /**
     * Konvertiert Content zu anderem Format und cached das Ergebnis
     */
    public function convertAndCache(ForumPost $post, string $targetFormat): string
    {
        $cacheField = "content_{$targetFormat}_cache";

        // Return cached wenn verfügbar
        if ($post->$cacheField) {
            return $post->$cacheField;
        }

        // Konvertiere
        $converted = $this->convert($post->content_original, $post->content_format, $targetFormat);

        // Cache speichern
        $post->update([$cacheField => $converted]);

        return $converted;
    }

    /**
     * Konvertiert zwischen BBCode und Markdown
     */
    public function convert(string $content, string $fromFormat, string $toFormat): string
    {
        if ($fromFormat === $toFormat) {
            return $content;
        }

        return match ([$fromFormat, $toFormat]) {
            ['bbcode', 'markdown'] => $this->bbcodeToMarkdown->convert($content),
            ['markdown', 'bbcode'] => $this->markdownToBBCode->convert($content),
            default => $content,
        };
    }

    /**
     * Invalidiert alle Caches für einen Post
     */
    public function invalidateCache(ForumPost $post): void
    {
        $post->update([
            'content_html' => null,
            'content_bbcode_cache' => null,
            'content_markdown_cache' => null,
        ]);

        Cache::forget('bbcode:' . md5($post->content_original));
        Cache::forget('markdown:' . md5($post->content_original));
    }
}
