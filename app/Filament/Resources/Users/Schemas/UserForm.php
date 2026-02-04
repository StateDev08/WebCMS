<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Toggle::make('is_premium')
                    ->required(),
                TextInput::make('locale')
                    ->required()
                    ->default('de'),
                TextInput::make('theme')
                    ->required()
                    ->default('default'),
                TextInput::make('theme_config'),
                TextInput::make('stripe_id'),
                TextInput::make('pm_type'),
                TextInput::make('pm_last_four'),
                DateTimePicker::make('trial_ends_at'),
                TextInput::make('posts_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('threads_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('last_activity_at'),
            ]);
    }
}
