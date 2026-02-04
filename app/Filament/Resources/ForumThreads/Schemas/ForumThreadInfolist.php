<?php

namespace App\Filament\Resources\ForumThreads\Schemas;

use App\Models\ForumThread;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ForumThreadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category_id')
                    ->numeric(),
                TextEntry::make('community_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                IconEntry::make('is_sticky')
                    ->boolean(),
                IconEntry::make('is_locked')
                    ->boolean(),
                TextEntry::make('pinned_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('views_count')
                    ->numeric(),
                TextEntry::make('posts_count')
                    ->numeric(),
                TextEntry::make('last_post_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('last_post_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ForumThread $record): bool => $record->trashed()),
            ]);
    }
}
