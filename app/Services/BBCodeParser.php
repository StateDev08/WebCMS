<?php

namespace App\Services;

use Genert\BBCode\BBCode;
use Illuminate\Support\Facades\Cache;

class BBCodeParser
{
    protected BBCode $parser;

    public function __construct()
    {
        $this->parser = new BBCode();
        $this->configureParsers();
    }

    protected function configureParsers()
    {
        // Erweitere Standard-BBCode mit Gaming-spezifischen Tags
        $this->parser->addParser(
            'spoiler',
            '/\[spoiler\](.*?)\[\/spoiler\]/s',
            '<details class="spoiler"><summary>Spoiler</summary>$1</details>'
        );

        $this->parser->addParser(
            'game',
            '/\[game=(.*?)\](.*?)\[\/game\]/s',
            '<span class="game-tag" data-game="$1">$2</span>'
        );

        $this->parser->addParser(
            'quote_user',
            '/\[quote user="(.*?)"\](.*?)\[\/quote\]/s',
            '<blockquote class="user-quote"><cite>$1 schrieb:</cite>$2</blockquote>'
        );
    }

    public function toHtml(string $bbcode): string
    {
        $cacheKey = 'bbcode:' . md5($bbcode);
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($bbcode) {
            return $this->parser->convertToHtml($bbcode);
        });
    }

    public function stripTags(string $bbcode): string
    {
        return strip_tags($this->toHtml($bbcode));
    }

    public function validate(string $bbcode): array
    {
        $errors = [];
        
        // Prüfe auf ungeschlossene Tags
        if (preg_match_all('/\[(\w+)(?:=.*?)?\]/i', $bbcode, $openTags)) {
            if (preg_match_all('/\[\/(\w+)\]/i', $bbcode, $closeTags)) {
                $openCount = array_count_values($openTags[1]);
                $closeCount = array_count_values($closeTags[1]);
                
                foreach ($openCount as $tag => $count) {
                    if (!isset($closeCount[$tag]) || $closeCount[$tag] < $count) {
                        $errors[] = "Ungeschlossener Tag: [{$tag}]";
                    }
                }
            }
        }
        
        return $errors;
    }
}
