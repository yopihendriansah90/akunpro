<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class ManageSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.manage-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::current();
        $this->form->fill([
            'store_name' => $setting->store_name,
            'whatsapp_number' => $setting->whatsapp_number,
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make($this->getFormActions())
                            ->fullWidth()
                            ->key('form-actions'),
                    ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('store_name')
                    ->label('Nama Brand / Toko')
                    ->required()
                    ->maxLength(100),
                TextInput::make('whatsapp_number')
                    ->label('Nomor WhatsApp Admin')
                    ->helperText('Format internasional tanpa tanda +, contoh: 6283116545674')
                    ->required()
                    ->regex('/^[0-9]+$/')
                    ->maxLength(20),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $this->form->validate();

        $setting = Setting::current();
        $setting->update($this->data);

        Notification::make()
            ->title('Pengaturan disimpan')
            ->success()
            ->send();
    }
}
