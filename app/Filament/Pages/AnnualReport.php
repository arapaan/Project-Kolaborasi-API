<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AnnualReport extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static string $view = 'filament.pages.annual-report';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Annual Report';    


}
