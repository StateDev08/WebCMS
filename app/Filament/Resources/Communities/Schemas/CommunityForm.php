<?php

namespace App\Filament\Resources\Communities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('game_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('logo'),
                TextInput::make('banner'),
                TextInput::make('theme_override'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('_lft')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('_rgt')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('parent_id')
                    ->numeric(),
            ]);
    }
}
