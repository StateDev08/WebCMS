<?php

namespace App\Services;

class MarkdownToBBCodeConverter
{
    protected array $conversions = [
        // Headers
        '/^# (.*?)$/m' => '[h1]$1[/h1]',
        '/^## (.*?)$/m' => '[h2]$1[/h2]',
        '/^### (.*?)$/m' => '[h3]$1[/h3]',
        
        // Bold and Italic
        '/\*\*\*(.+?)\*\*\*/s' => '[b][i]$1[/i][/b]',
        '/\*\*(.+?)\*\*/s' => '[b]$1[/b]',
        '/\*(.+?)\*/s' => '[i]$1[/i]',
        '/__(.+?)__/s' => '[b]$1[/b]',
        '/_(.+?)_/s' => '[i]$1[/i]',
        
        // Strikethrough
        '/~~(.+?)~~/s' => '[s]$1[/s]',
        
        // Links
        '/\[([^\]]+)\]\(([^\)]+)\)/' => '[url=$2]$1[/url]',
        '/<(https?:\/\/[^>]+)>/' => '[url]$1[/url]',
        
        // Images
        '/!\[([^\]]*)\]\(([^\)]+)\)/' => '[img]$2[/img]',
        
        // Code blocks
        '/```(\w+)?\n(.*?)```/s' => '[code=$1]$2[/code]',
        '/`([^`]+)`/' => '[code]$1[/code]',
        
        // Quotes
        '/^> (.+)$/m' => '[quote]$1[/quote]',
        
        // Lists
        '/^\* (.+)$/m' => '[*]$1',
        '/^\d+\. (.+)$/m' => '[*]$1',
    ];

    public function convert(string $markdown): string
    {
        $bbcode = $markdown;
        
        // Apply conversions
        foreach ($this->conversions as $pattern => $replacement) {
            $bbcode = preg_replace($pattern, $replacement, $bbcode);
        }
        
        // Wrap lists
        $bbcode = $this->wrapLists($bbcode);
        
        return $bbcode;
    }

    protected function wrapLists(string $text): string
    {
        // Find consecutive list items and wrap them
        $lines = explode("\n", $text);
        $result = [];
        $inList = false;
        
        foreach ($lines as $line) {
            if (str_starts_with($line, '[*]')) {
                if (!$inList) {
                    $result[] = '[list]';
                    $inList = true;
                }
                $result[] = $line;
            } else {
                if ($inList) {
                    $result[] = '[/list]';
                    $inList = false;
                }
                $result[] = $line;
            }
        }
        
        if ($inList) {
            $result[] = '[/list]';
        }
        
        return implode("\n", $result);
    }
}
