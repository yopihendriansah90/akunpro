<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('role')
                    ->label('Peran'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('text')
                    ->label('Testimoni')
                    ->limit(60)
                    ->searchable(),
                IconColumn::make('available')
                    ->label('Tampil')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
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
