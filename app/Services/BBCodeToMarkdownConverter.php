<?php

namespace App\Services;

class BBCodeToMarkdownConverter
{
    protected array $conversions = [
        // Basic formatting
        '/\[b\](.*?)\[\/b\]/is' => '**$1**',
        '/\[i\](.*?)\[\/i\]/is' => '*$1*',
        '/\[u\](.*?)\[\/u\]/is' => '<u>$1</u>', // Markdown hat kein natives underline
        '/\[s\](.*?)\[\/s\]/is' => '~~$1~~',
        
        // Headers
        '/\[h1\](.*?)\[\/h1\]/is' => '# $1',
        '/\[h2\](.*?)\[\/h2\]/is' => '## $1',
        '/\[h3\](.*?)\[\/h3\]/is' => '### $1',
        
        // Links and Images
        '/\[url=(.*?)\](.*?)\[\/url\]/is' => '[$2]($1)',
        '/\[url\](.*?)\[\/url\]/is' => '<$1>',
        '/\[img\](.*?)\[\/img\]/is' => '![]($1)',
        
        // Lists
        '/\[\*\]\s*(.*?)(?=\[\*\]|\[\/list\])/is' => '* $1' . "\n",
        
        // Quotes
        '/\[quote\](.*?)\[\/quote\]/is' => '> $1',
        '/\[quote="(.*?)"\](.*?)\[\/quote\]/is' => '> **$1 schrieb:**' . "\n" . '> $2',
        
        // Code
        '/\[code\](.*?)\[\/code\]/is' => '```' . "\n" . '$1' . "\n" . '```',
        '/\[code=(.*?)\](.*?)\[\/code\]/is' => '```$1' . "\n" . '$2' . "\n" . '```',
    ];

    public function convert(string $bbcode): string
    {
        $markdown = $bbcode;
        
        // Handle lists specially
        $markdown = preg_replace('/\[list\](.*?)\[\/list\]/is', "$1", $markdown);
        $markdown = preg_replace('/\[list=1\](.*?)\[\/list\]/is', "$1", $markdown);
        
        // Apply conversions
        foreach ($this->conversions as $pattern => $replacement) {
            $markdown = preg_replace($pattern, $replacement, $markdown);
        }
        
        // Cleanup
        $markdown = $this->cleanupWhitespace($markdown);
        
        return $markdown;
    }

    protected function cleanupWhitespace(string $text): string
    {
        // Entferne mehrfache Leerzeilen
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        
        // Trimme Zeilen
        $lines = explode("\n", $text);
        $lines = array_map('rtrim', $lines);
        
        return implode("\n", $lines);
    }
}
