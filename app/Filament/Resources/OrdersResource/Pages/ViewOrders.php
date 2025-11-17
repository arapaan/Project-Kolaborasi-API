<?php

namespace App\Filament\Resources\OrdersResource\Pages;

use App\Filament\Resources\OrdersResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = OrdersResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // ✅ Informasi Umum (Created At + Customer Name)
                Card::make([
                    TextEntry::make('created_at')
                        ->label('Created At'),

                    TextEntry::make('customer_name')
                        ->label('Customer Name'),
                ])->columns(2),

                // ✅ Informasi Pelanggan
                Card::make([
                    TextEntry::make('email')->label('Email Address'),
                    TextEntry::make('full_address')->label('Address'),
                    TextEntry::make('additional_note')->label('Additional Notes'),
                ])->columns(3),

                // ✅ Daftar Makanan/Minuman Dibeli
                Card::make([
                    RepeatableEntry::make('items')
                        ->label('Makanan/Minuman Dibeli')
                        ->schema([
                            TextEntry::make('name')->label('Nama Makanan/Minuman'),

                            TextEntry::make('quantity')->label('Jumlah'),

                            TextEntry::make('price')
                                ->label('Harga Satuan')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),

                            TextEntry::make('total_price')
                                ->label('Total Harga')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                                ->color('success')
                                ->weight('bold')
                                ->alignStart(),
                        ])
                        ->columns(4)
                        ->columnSpanFull(),
                ]),

                // ✅ Grand Total
                Card::make([
                    TextEntry::make('total')
                        ->label('Grand Total')
                        ->numeric(decimalPlaces: 0)
                        ->money('IDR', locale: 'id')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->color('success')
                        ->weight('bold')
                        ->alignEnd()
                        ->columnSpanFull(),
                ]),
            ]);
    }
}