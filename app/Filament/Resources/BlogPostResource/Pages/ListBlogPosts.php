<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use App\Services\BlogPostSyncService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync')
                ->label('Sync from Hashnode')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Sync blog posts from Hashnode')
                ->modalDescription('Pulls the latest post titles and metadata from your Hashnode publication. Existing visibility settings are preserved.')
                ->action(function (BlogPostSyncService $sync): void {
                    $count = $sync->sync();

                    Notification::make()
                        ->title("Synced {$count} posts from Hashnode")
                        ->success()
                        ->send();
                }),
        ];
    }
}
