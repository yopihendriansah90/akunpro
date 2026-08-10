<?php

namespace App\Filament\Resources\Products\Tables;

use App\Support\WhatsApp;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->label('Foto')
                    ->collection('images')
                    ->conversion('thumb')
                    ->square()
                    ->size(40)
                    ->defaultImageUrl(fn () => 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"><rect width="40" height="40" rx="10" fill="%23fef3c7"/></svg>'),
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('amber'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => WhatsApp::formatRupiah($state))
                    ->sortable(),
                TextColumn::make('original_price')
                    ->label('Harga Coret')
                    ->formatStateUsing(fn ($state) => $state ? WhatsApp::formatRupiah($state) : '-'),
                TextColumn::make('duration')
                    ->label('Masa Aktif')
                    ->badge()
                    ->color('info'),
                TextColumn::make('warranty')
                    ->label('Garansi')
                    ->badge()
                    ->color('success'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('badge')
                    ->label('Badge')
                    ->badge()
                    ->color('violet'),
                IconColumn::make('available')
                    ->label('Tersedia')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                TernaryFilter::make('available')
                    ->label('Status'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
