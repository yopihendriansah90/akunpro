<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Foto Produk')
                    ->description('Upload & edit foto (crop, rotasi) sebelum disimpan. Rasio persegi 1:1.')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Foto Produk')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorMode(2)
                            ->imageCropAspectRatio('1:1')
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                                '16:9',
                                null,
                            ])
                            ->directory('products')
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('1000')
                            ->imageResizeTargetHeight('1000')
                            ->maxSize(4096)
                            ->columnSpanFull(),
                    ]),
                Section::make('Informasi Produk')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->options(fn () => Category::ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('badge')
                            ->label('Badge (opsional)')
                            ->placeholder('Contoh: Terlaris, Populer')
                            ->maxLength(50),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull()
                            ->required(),
                    ]),
                Section::make('Harga')
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga Jual (Rp)')
                            ->numeric()
                            ->required()
                            ->minValue(0),
                        TextInput::make('original_price')
                            ->label('Harga Coret (Rp)')
                            ->numeric()
                            ->minValue(0)
                            ->nullable(),
                    ]),
                Section::make('Detail & Status')
                    ->schema([
                        TextInput::make('duration')
                            ->label('Masa Aktif')
                            ->placeholder('Contoh: 3 bulan')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('warranty')
                            ->label('Garansi')
                            ->placeholder('Contoh: 1 bulan')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('icon')
                            ->label('Ikon (Material Symbols)')
                            ->helperText('Contoh: auto_awesome, smart_toy, video_settings, palette, music_note, subscriptions')
                            ->default('apps')
                            ->maxLength(50),
                        Select::make('rating')
                            ->label('Rating')
                            ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                            ->default(5)
                            ->required(),
                        Toggle::make('available')
                            ->label('Tersedia')
                            ->default(true),
                        TextInput::make('sort')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
