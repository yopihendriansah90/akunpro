<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Pages\Concerns\HandlesProductImageUpload;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use HandlesProductImageUpload;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->captureProductImage($data);
    }

    protected function afterSave(): void
    {
        $this->syncProductImage();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
