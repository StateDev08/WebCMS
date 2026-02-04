<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block as BuilderBlock;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inhalt')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required(),
                        Textarea::make('excerpt')
                            ->rows(3),
                        Select::make('status')
                            ->options([
                                'draft' => 'Entwurf',
                                'published' => 'Veröffentlicht',
                            ])
                            ->required(),
                        DateTimePicker::make('published_at'),
                        Builder::make('blocks')
                            ->label('Inhaltsblöcke')
                            ->blocks([
                                BuilderBlock::make('text')
                                    ->schema([
                                        Textarea::make('text')
                                            ->rows(6)
                                            ->required(),
                                    ]),
                                BuilderBlock::make('image')
                                    ->schema([
                                        TextInput::make('url')
                                            ->label('Bild-URL')
                                            ->required(),
                                        TextInput::make('alt')
                                            ->label('Alt-Text'),
                                    ]),
                                BuilderBlock::make('button')
                                    ->schema([
                                        TextInput::make('label')
                                            ->required(),
                                        TextInput::make('url')
                                            ->required(),
                                        Select::make('style')
                                            ->options([
                                                'primary' => 'Primary',
                                                'secondary' => 'Secondary',
                                            ])
                                            ->default('primary'),
                                    ]),
                                BuilderBlock::make('gallery')
                                    ->schema([
                                        Repeater::make('images')
                                            ->schema([
                                                TextInput::make('url')
                                                    ->label('Bild-URL')
                                                    ->required(),
                                                TextInput::make('alt')
                                                    ->label('Alt-Text'),
                                            ])
                                            ->minItems(1)
                                            ->columns(2),
                                    ]),
                                BuilderBlock::make('columns')
                                    ->schema([
                                        Repeater::make('columns')
                                            ->schema([
                                                Textarea::make('text')
                                                    ->rows(4)
                                                    ->required(),
                                            ])
                                            ->minItems(2)
                                            ->maxItems(3),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title'),
                        Textarea::make('seo_description'),
                        TextInput::make('seo_image'),
                        Toggle::make('seo_image_enabled')
                            ->label('SEO-Bild im Hero verwenden')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
