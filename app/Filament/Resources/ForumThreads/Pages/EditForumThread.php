<?php

namespace App\Filament\Resources\ForumThreads\Pages;

use App\Filament\Resources\ForumThreads\ForumThreadResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditForumThread extends EditRecord
{
    protected static string $resource = ForumThreadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
