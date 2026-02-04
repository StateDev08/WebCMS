<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;
use Illuminate\Support\Facades\Cache;

class MarkdownParser
{
    protected CommonMarkConverter $converter;

    public function __construct()
    {
        $config = [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 10,
        ];

        $environment = new Environment($config);
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new TableExtension());

        $this->converter = new CommonMarkConverter($config, $environment);
    }

    public function toHtml(string $markdown): string
    {
        $cacheKey = 'markdown:' . md5($markdown);
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($markdown) {
            return $this->converter->convert($markdown)->getContent();
        });
    }

    public function stripFormatting(string $markdown): string
    {
        return strip_tags($this->toHtml($markdown));
    }

    public function validate(string $markdown): array
    {
        $errors = [];
        
        try {
            $this->converter->convert($markdown);
        } catch (\Exception $e) {
            $errors[] = 'Markdown Parsing Error: ' . $e->getMessage();
        }
        
        return $errors;
    }
}
