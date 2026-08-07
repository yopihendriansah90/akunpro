<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(50),
                TextInput::make('icon')
                    ->label('Ikon (Material Symbols)')
                    ->helperText('Contoh: chat, palette, music_note, tv, video_settings. Lihat https://fonts.google.com/icons')
                    ->default('label')
                    ->maxLength(50),
                TextInput::make('sort')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
