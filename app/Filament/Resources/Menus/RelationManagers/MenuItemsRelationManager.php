<?php

namespace App\Filament\Resources\Menus\RelationManagers;

use App\Models\MenuItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('label')
                    ->required()
                    ->maxLength(120),
                TextInput::make('url')
                    ->placeholder('/cms/pages/home')
                    ->maxLength(255),
                Select::make('parent_id')
                    ->label('Parent')
                    ->options(fn () => MenuItem::query()
                        ->where('menu_id', $this->getOwnerRecord()->id)
                        ->pluck('label', 'id')
                        ->toArray())
                    ->searchable()
                    ->nullable(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('parent.label')
                    ->label('Parent')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
