<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    // protected static ?string $title = '👋Welcome (User)';

    protected static string $view = 'filament.pages.custom-dashboard';

    public function getTitle(): string
    {
        $user = auth()->user();

        return '👋 Welcome ' . ($user?->name ?? 'Guest');
    }
}
