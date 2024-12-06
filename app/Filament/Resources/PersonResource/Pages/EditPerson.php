<?php

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerson extends EditRecord
{

	protected static string $resource = PersonResource::class;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Trash'),
            Actions\ForceDeleteAction::make()->label('Delete'),
            Actions\RestoreAction::make(),
            Actions\Action::make('Save changes')->button()->action('save')
        ];

    }
}
