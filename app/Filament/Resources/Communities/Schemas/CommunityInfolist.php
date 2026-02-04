<?php

namespace App\Filament\Resources\Communities\Schemas;

use App\Models\Community;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CommunityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('game_id')
                    ->numeric(),
                TextEntry::make('slug'),
                TextEntry::make('logo')
                    ->placeholder('-'),
                TextEntry::make('banner')
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('_lft')
                    ->numeric(),
                TextEntry::make('_rgt')
                    ->numeric(),
                TextEntry::make('parent_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Community $record): bool => $record->trashed()),
            ]);
    }
}
