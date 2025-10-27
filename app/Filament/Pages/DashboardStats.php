<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Filament\Widgets\StatsOverview;

class DashboardStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.custom-dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    public function getTitle(): string
    {
        $user = auth()->user();

        return '👋 Welcome ' . ($user?->name ?? 'Guest');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    public $reportData = [];
    public $year;

    public function mount()
    {
        $this->year = now()->year;
        $this->generateReport();
    }

    public function generateReport()
    {
        $year = $this->year;

        $this->reportData = [
            'total_revenue' => Order::whereYear('created_at', $year)->sum('total_price'),
            'monthly_revenue' => Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get(),
            'total_customers' => Order::whereYear('created_at', $year)->distinct('user_id')->count('user_id'),
            'sold_products' => DB::table('order_product')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->select('products.name', DB::raw('SUM(order_product.quantity) as total_sold'))
                ->groupBy('products.name')
                ->get(),
        ];
    }

    public function downloadPdf()
    {
        $pdf = Pdf::loadView('filament.pages.annual-report', [
            'data' => $this->reportData,
            'year' => $this->year,
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "AnnualReport-{$this->year}.pdf" 
        );
    }
}
