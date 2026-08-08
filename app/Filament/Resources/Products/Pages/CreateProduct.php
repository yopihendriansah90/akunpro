<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Pages\Concerns\HandlesProductImageUpload;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use HandlesProductImageUpload;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->captureProductImage($data);
    }

    protected function afterCreate(): void
    {
        $this->syncProductImage();
    }
}
