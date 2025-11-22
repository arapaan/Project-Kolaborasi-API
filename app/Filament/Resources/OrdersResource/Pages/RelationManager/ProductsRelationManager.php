<?php

namespace App\Filament\Resources\OrdersResource\ProductsRelationManagers;

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
                    ->label('Jumlah')
                    ->sortable(),

                TextColumn::make('pivot.price')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('pivot.total_price')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->headerActions([]) // tidak bisa tambah dari sini
            ->actions([]);      // tidak bisa edit/hapus dari sini
    }
}
