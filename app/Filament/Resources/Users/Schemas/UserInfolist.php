<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('is_premium')
                    ->boolean(),
                TextEntry::make('locale'),
                TextEntry::make('theme'),
                TextEntry::make('stripe_id')
                    ->placeholder('-'),
                TextEntry::make('pm_type')
                    ->placeholder('-'),
                TextEntry::make('pm_last_four')
                    ->placeholder('-'),
                TextEntry::make('trial_ends_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('posts_count')
                    ->numeric(),
                TextEntry::make('threads_count')
                    ->numeric(),
                TextEntry::make('last_activity_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (User $record): bool => $record->trashed()),
            ]);
    }
}
