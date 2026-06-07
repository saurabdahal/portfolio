<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $navigationGroup = 'Portfolio';
    protected static ?int $navigationSort = 99;
    protected static string $view = 'filament.pages.site-settings';

    public bool $maintenance_mode = false;

    public function mount(): void
    {
        $this->maintenance_mode = Setting::get('maintenance_mode', '0') === '1';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Maintenance')
                    ->description('When enabled, visitors see a maintenance page instead of the portfolio.')
                    ->schema([
                        Toggle::make('maintenance_mode')
                            ->label('Maintenance Mode')
                            ->helperText('Toggle this to take your site offline for visitors.')
                            ->onColor('danger')
                            ->offColor('success'),
                    ]),
            ])
            ->statePath('');
    }

    public function save(): void
    {
        Setting::set('maintenance_mode', $this->maintenance_mode ? '1' : '0');

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
