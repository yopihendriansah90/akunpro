<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\RawJs;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 2,
            ])
            ->components([
                Section::make('Foto Produk')
                    ->description('Gunakan gambar persegi agar tampilan katalog konsisten.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Foto Produk')
                            ->collection('images')
                            ->disk(config('media-library.disk_name', 'public'))
                            ->conversionsDisk(config('media-library.conversions_disk_name'))
                            ->image()
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('JPG, PNG, atau WebP. Maksimal 4 MB. Rekomendasi 1000 × 1000 px.')
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
                            ->imagePreviewHeight('250')
                            ->panelAspectRatio('16:9')
                            ->panelLayout('compact')
                            ->maxSize(4096)
                            ->multiple(false)
                            ->maxFiles(1)
                            ->preserveFilenames(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Informasi Produk')
                    ->description('Informasi yang akan dilihat pelanggan di katalog.')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->options(fn () => Category::ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->placeholder('Contoh: ChatGPT Plus')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('badge')
                            ->label('Badge (opsional)')
                            ->placeholder('Contoh: Terlaris, Populer')
                            ->maxLength(50),
                        RichEditor::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Jelaskan manfaat, fitur, dan target pengguna produk.')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike'],
                                ['bulletList', 'orderedList', 'blockquote'],
                                ['link', 'undo', 'redo'],
                            ])
                            ->columnSpanFull()
                            ->maxLength(5000)
                            ->required(),
                    ]),
                Section::make('Harga')
                    ->description('Masukkan angka Rupiah tanpa titik atau simbol. Contoh: 30000.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga Jual (Rp)')
                            ->placeholder('30000')
                            ->prefix('Rp')
                            ->inputMode('numeric')
                            ->mask(RawJs::make(<<<'JS'
                                $money($input, ',', '.')
                            JS))
                            ->stripCharacters(['.', ','])
                            ->numeric()
                            ->integer()
                            ->required()
                            ->minValue(0)
                            ->maxValue(4294967295),
                        TextInput::make('original_price')
                            ->label('Harga Coret (Rp)')
                            ->placeholder('45000')
                            ->prefix('Rp')
                            ->inputMode('numeric')
                            ->mask(RawJs::make(<<<'JS'
                                $money($input, ',', '.')
                            JS))
                            ->stripCharacters(['.', ','])
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->maxValue(4294967295)
                            ->gt('price')
                            ->nullable(),
                    ]),
                Section::make('Detail & Status')
                    ->description('Atur masa berlaku, garansi, ikon, dan visibilitas produk.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('duration')
                            ->label('Masa Aktif')
                            ->placeholder('Contoh: 3 bulan')
                            ->helperText('Contoh: 1 bulan, 3 bulan, 1 tahun.')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('warranty')
                            ->label('Garansi')
                            ->placeholder('Contoh: 1 bulan')
                            ->helperText('Tulis masa garansi yang dijanjikan.')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('icon')
                            ->label('Ikon (Material Symbols)')
                            ->helperText('Klik tombol pilih ikon untuk membuka galeri ikon.')
                            ->default('apps')
                            ->readOnly()
                            ->suffixAction(
                                Action::make('chooseIcon')
                                    ->label('Pilih ikon')
                                    ->icon('heroicon-m-sparkles')
                                    ->tooltip('Pilih ikon dari galeri')
                                    ->modalHeading('Pilih ikon produk')
                                    ->modalDescription('Cari lalu pilih ikon yang paling sesuai dengan produk.')
                                    ->modalSubmitActionLabel('Gunakan ikon')
                                    ->modalWidth('3xl')
                                    ->fillForm(fn (Get $schemaGet): array => [
                                        'selected_icon' => $schemaGet('icon') ?: 'apps',
                                    ])
                                    ->form([
                                        Select::make('selected_icon')
                                            ->label('Ikon')
                                            ->options(self::iconOptions())
                                            ->searchable()
                                            ->allowHtml()
                                            ->native(false)
                                            ->required(),
                                    ])
                                    ->action(function (array $data, Set $schemaSet): void {
                                        $schemaSet('icon', $data['selected_icon']);
                                    }),
                            )
                            ->regex('/^[a-z0-9_]+$/')
                            ->maxLength(50),
                        Select::make('rating')
                            ->label('Rating')
                            ->native(false)
                            ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                            ->default(5)
                            ->required(),
                        Toggle::make('available')
                            ->label('Tersedia')
                            ->helperText('Tampilkan produk di katalog publik.')
                            ->default(true),
                        TextInput::make('sort')
                            ->label('Urutan')
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->maxValue(4294967295)
                            ->default(0),
                    ]),
            ]);
    }

    /**
     * Keep the picker curated so the modal stays quick to scan while still
     * covering the common product categories used in the storefront.
     *
     * @return array<string, string>
     */
    protected static function iconOptions(): array
    {
        $icons = [
            'apps', 'auto_awesome', 'smart_toy', 'subscriptions', 'chat',
            'shopping_bag', 'shopping_cart', 'workspace_premium', 'verified',
            'verified_user', 'security', 'shield', 'lock', 'key',
            'bolt', 'rocket_launch', 'speed', 'star', 'favorite',
            'diamond', 'redeem', 'local_offer', 'sell', 'payments',
            'account_circle', 'person', 'group', 'school', 'work',
            'business_center', 'cloud', 'cloud_done', 'download', 'language',
            'movie', 'music_note', 'palette', 'photo_camera', 'sports_esports',
            'settings', 'support_agent', 'wifi', 'devices', 'tv',
        ];

        return collect($icons)->mapWithKeys(fn (string $icon): array => [
            $icon => '<span class="inline-flex items-center gap-2"><span class="material-symbols-rounded text-lg">' . e($icon) . '</span><span>' . e(str_replace('_', ' ', ucwords($icon, '_'))) . '</span></span>',
        ])->all();
    }
}
