<?php

namespace App\Filament\Resources\ForumThreads;

use App\Filament\Resources\ForumThreads\Pages\CreateForumThread;
use App\Filament\Resources\ForumThreads\Pages\EditForumThread;
use App\Filament\Resources\ForumThreads\Pages\ListForumThreads;
use App\Filament\Resources\ForumThreads\Pages\ViewForumThread;
use App\Filament\Resources\ForumThreads\Schemas\ForumThreadForm;
use App\Filament\Resources\ForumThreads\Schemas\ForumThreadInfolist;
use App\Filament\Resources\ForumThreads\Tables\ForumThreadsTable;
use App\Models\ForumThread;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ForumThreadResource extends Resource
{
    protected static ?string $model = ForumThread::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ForumThreadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ForumThreadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ForumThreadsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListForumThreads::route('/'),
            'create' => CreateForumThread::route('/create'),
            'view' => ViewForumThread::route('/{record}'),
            'edit' => EditForumThread::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
