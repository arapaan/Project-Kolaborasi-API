<?php

namespace App\Filament\Resources\OrdersResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Produk Dipesan';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),

                TextColumn::make('pivot.quantity')
                    ->label('Jumlah'),

                // HARGA SATUAN (format Rupiah)
                TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->formatStateUsing(fn($state) =>
                        'Rp ' . number_format((float) $state, 0, ',', '.')
                    ),

                // SUBTOTAL (format Rupiah)
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->getStateUsing(fn ($record) =>
                        (float) $record->price * (int) $record->pivot->quantity
                    )
                    ->formatStateUsing(fn($state) =>
                        'Rp ' . number_format((float) $state, 0, ',', '.')
                    ),

            ])
            ->headerActions([])
            ->actions([])
            ->paginated(false);
    }
}
