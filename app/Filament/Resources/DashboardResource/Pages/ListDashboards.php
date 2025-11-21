<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf; // ← tambahkan ini

class ListDashboards extends ListRecords
{
    protected static string $resource = DashboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('downloadPdf')   // ← tombol PDF
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    // ambil semua data Dashboard
                    $data = $this->getTableQuery()->get();

                    // buat pdf memakai view PDF (kamu harus bikin file ini)
                    $pdf = Pdf::loadView('pdf.dashboard', [
                        'data' => $data
                    ]);

                    // stream download
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'dashboard-data.pdf'
                    );
                }),
        ];
    }
}
