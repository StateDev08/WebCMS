<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('community_id')
                    ->required()
                    ->numeric(),
                TextInput::make('leader_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug'),
                TextInput::make('tag'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('logo'),
                TextInput::make('max_members')
                    ->required()
                    ->numeric()
                    ->default(50),
                Toggle::make('is_recruiting')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('settings'),
            ]);
    }
}
