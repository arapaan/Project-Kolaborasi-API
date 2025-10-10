<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrdersResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrdersResource\RelationManagers;

class OrdersResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('business_id')
                ->relationship('business', 'name_company')
                ->required(),
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),            
            TextInput::make('total_price')
                ->numeric()
                ->required(),
            Select::make('product')
                ->multiple()
                ->relationship('product', 'name'),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pemesan')
                    ->searchable()->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()->sortable(),
                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->searchable()->sortable(),
                TextColumn::make('product')
                    ->label('Product')
                    ->searchable()->sortable(),
                TextColumn::make('created_by')
                    ->label('Staff')
                    ->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrders::route('/create'),
            'edit' => Pages\EditOrders::route('/{record}/edit'),
        ];
    }
}
