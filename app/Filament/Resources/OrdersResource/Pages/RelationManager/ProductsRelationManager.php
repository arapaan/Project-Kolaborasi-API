<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Produk'),
                TextColumn::make('pivot.quantity')->label('Jumlah'),
                TextColumn::make('pivot.quantity')
                    ->label('Subtotal')
                    ->formatStateUsing(function ($record) {
                        return $record->pivot->quantity * ($record->price ?? 0);
                    }),
            ]);
    }
}
