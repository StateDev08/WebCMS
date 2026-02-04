<?php

namespace App\Filament\Resources\ForumThreads\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ForumThreadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('community_id')
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Toggle::make('is_sticky')
                    ->required(),
                Toggle::make('is_locked')
                    ->required(),
                DateTimePicker::make('pinned_at'),
                TextInput::make('views_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('posts_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('last_post_id')
                    ->numeric(),
                DateTimePicker::make('last_post_at'),
            ]);
    }
}
