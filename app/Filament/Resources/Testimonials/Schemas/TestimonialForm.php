<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100),
                TextInput::make('role')
                    ->label('Peran (opsional)')
                    ->placeholder('Contoh: Mahasiswa, Kreator Konten')
                    ->maxLength(100),
                Select::make('rating')
                    ->label('Rating')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                    ->default(5)
                    ->required(),
                Textarea::make('text')
                    ->label('Testimoni')
                    ->rows(3)
                    ->maxLength(1000)
                    ->required(),
                Toggle::make('available')
                    ->label('Tampilkan')
                    ->default(true),
                TextInput::make('sort')
                    ->label('Urutan')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->maxValue(4294967295)
                    ->default(0),
            ]);
    }
}
