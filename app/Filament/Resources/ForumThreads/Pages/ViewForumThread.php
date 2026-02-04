<?php

namespace App\Filament\Resources\ForumThreads\Pages;

use App\Filament\Resources\ForumThreads\ForumThreadResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewForumThread extends ViewRecord
{
    protected static string $resource = ForumThreadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
