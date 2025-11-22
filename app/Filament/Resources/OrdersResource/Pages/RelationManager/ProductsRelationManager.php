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

            TextColumn::make('price')
                ->label('Harga Satuan')
                ->money('IDR'),

            TextColumn::make('subtotal')
                ->label('Subtotal')
                ->money('IDR')
                ->getStateUsing(function ($record) {
                    return $record->price * $record->pivot->quantity;
                }),
        ])
        ->headerActions([])
        ->actions([]);
}

}
