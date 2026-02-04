<?php

namespace App\Filament\Resources\Teams\Schemas;

use App\Models\Team;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('community_id')
                    ->numeric(),
                TextEntry::make('leader_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('tag')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('logo')
                    ->placeholder('-'),
                TextEntry::make('max_members')
                    ->numeric(),
                IconEntry::make('is_recruiting')
                    ->boolean(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Team $record): bool => $record->trashed()),
            ]);
    }
}
