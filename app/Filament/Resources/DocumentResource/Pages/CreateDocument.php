<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Notifications\NewDocumentNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function afterCreate(): void
    {
        $document = $this->record;
        
        // Notify the related employee
        if ($document->employee) {
            $document->employee->notify(new NewDocumentNotification($document));
        }
    }
}
