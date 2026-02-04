<?php

namespace App\Filament\Resources\Games\Schemas;

use App\Models\Game;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GameInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('slug'),
                TextEntry::make('icon')
                    ->placeholder('-'),
                TextEntry::make('banner')
                    ->placeholder('-'),
                TextEntry::make('website')
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Game $record): bool => $record->trashed()),
            ]);
    }
}
