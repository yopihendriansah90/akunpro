<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Support\WhatsApp;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentProductsTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table->heading('Produk Terbaru')->description('Lima produk terakhir yang ditambahkan ke katalog.')
            ->query(Product::query()->with('category:id,name')->latest('created_at')->limit(5))
            ->columns([
                TextColumn::make('name')->label('Produk')->weight('bold')->limit(42),
                TextColumn::make('category.name')->label('Kategori')->badge()->color('warning'),
                TextColumn::make('price')->label('Harga')->formatStateUsing(fn ($state): string => WhatsApp::formatRupiah((int) $state)),
                IconColumn::make('available')->label('Aktif')->boolean(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->since(),
            ])->paginated(false)->headerActions([
                Action::make('viewAll')->label('Lihat semua')->url(ProductResource::getUrl('index')),
            ]);
    }
}
