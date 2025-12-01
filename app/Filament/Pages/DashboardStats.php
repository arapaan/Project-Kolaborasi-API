<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use App\Filament\Widgets\StatsOverview;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;

class DashboardStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.custom-dashboard';
    protected static ?string $navigationLabel = 'Dashboard';

    // PROPERTIES
    public $reportData = [];
    public $year;

    public function mount(): void
    {
        $this->year = now()->year;
        $this->generateReport();
    }

    public function getTitle(): string
    {
        $user = auth()->user();
        return '👋 Welcome ' . ($user?->name ?? 'Guest');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('📥 Download Laporan')
                ->color('success')
                ->modalHeading('📊 Download Laporan Bulanan')
                ->modalDescription('Pilih tahun dan bulan untuk laporan PDF')
                ->modalSubmitActionLabel('📥 Download PDF')
                ->modalCancelActionLabel('Batal')
                ->form([
                    Select::make('selectedYear')
                        ->label('Tahun')
                        ->options([
                            2024 => '2024',
                            2025 => '2025', 
                            2026 => '2026',
                        ])
                        ->default(now()->year)
                        ->required(),
                    
                    CheckboxList::make('selectedMonths')
                        ->label('Pilih Bulan')
                        ->options([
                            1 => 'Januari',
                            2 => 'Februari', 
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ])
                        ->default([1,2,3,4,5,6,7,8,9,10,11,12])
                        ->columns(3)
                        ->gridDirection('row')
                        ->required(),
                ])
                ->action(function (array $data) {
                    return $this->downloadFilteredPdf($data);
                })
        ];
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
                ->get()
                ->toArray(),
        ];
    }

    // METHOD DOWNLOAD PDF DENGAN FILTER BULAN
    public function downloadFilteredPdf(array $data)
    {
        $year = $data['selectedYear'];
        $months = $data['selectedMonths'];

        // GENERATE REPORT DATA DENGAN FILTER BULAN
        $reportData = [
            'total_revenue' => Order::whereYear('created_at', $year)
                ->when(!empty($months), function($query) use ($months) {
                    return $query->whereIn(DB::raw('MONTH(created_at)'), $months);
                })
                ->sum('total_price'),
                
            'monthly_revenue' => Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )->whereYear('created_at', $year)
            ->when(!empty($months), function($query) use ($months) {
                return $query->whereIn(DB::raw('MONTH(created_at)'), $months);
            })
            ->groupBy('month')
            ->orderBy('month')
            ->get(),
            
            'total_customers' => Order::whereYear('created_at', $year)
                ->when(!empty($months), function($query) use ($months) {
                    return $query->whereIn(DB::raw('MONTH(created_at)'), $months);
                })
                ->distinct('user_id')
                ->count('user_id'),
                
            'sold_products' => DB::table('order_product')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->select('products.name', DB::raw('SUM(order_product.quantity) as total_sold'))
                ->whereYear('orders.created_at', $year)
                ->when(!empty($months), function($query) use ($months) {
                    return $query->whereIn(DB::raw('MONTH(orders.created_at)'), $months);
                })
                ->groupBy('products.name')
                ->get()
                ->toArray(),
        ];

        // TENTUKAN JUDUL BERDASARKAN BULAN YANG DIPILIH
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        if (empty($months) || count($months) === 0) {
            $title = "Laporan {$year}";
            $filename = "Laporan-{$year}.pdf";
        } elseif (count($months) === 12) {
            $title = "Laporan Tahunan {$year}";
            $filename = "Laporan-Tahunan-{$year}.pdf";
        } elseif (count($months) === 1) {
            $monthName = $monthNames[$months[0]];
            $title = "Laporan {$monthName} {$year}";
            $filename = "Laporan-{$monthName}-{$year}.pdf";
        } else {
            $firstMonth = $monthNames[min($months)];
            $lastMonth = $monthNames[max($months)];
            $title = "Laporan {$firstMonth}-{$lastMonth} {$year}";
            $filename = "Laporan-{$firstMonth}-{$lastMonth}-{$year}.pdf";
        }

        // GENERATE PDF
        $pdf = Pdf::loadView('filament.pages.annual-report', [
            'data' => $reportData,
            'year' => $year,
            'title' => $title,
            'selectedMonths' => $months
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    // METHOD EXISTING (backward compatibility - untuk button lama)
    public function downloadPdf()
    {
        $data = [
            'selectedYear' => $this->year,
            'selectedMonths' => range(1, 12)
        ];
        return $this->downloadFilteredPdf($data);
    }
}
