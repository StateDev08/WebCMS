<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
