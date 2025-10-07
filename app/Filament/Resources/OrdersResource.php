<?php

namespace App\Filament\Resources;

use App\Models\Order;
use App\Models\User;
use App\Models\Business;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrdersResource\Pages;

class OrdersResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $pluralModelLabel = 'Orders';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Select::make('business_id')
                ->label('Business')
                ->relationship('business', 'name_company')
                ->required(),

            Select::make('user_id')
                ->label('User')
                ->relationship('user', 'name')
                ->required(),

            Select::make('created_by')
                ->label('Created By')
                ->relationship('creator', 'name') // relasi baru dijelaskan di bawah
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending')
                ->required(),

            TextInput::make('total_price')
                ->label('Total Price (IDR)')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('name_customer')->searchable(),
            TextColumn::make('business.name_company')
                ->label('Business')
                ->sortable()
                ->toggleable(),
            TextColumn::make('user.name')
                ->label('User')
                ->sortable()
                ->toggleable(),
            TextColumn::make('creator.name')
                ->label('Created By')
                ->sortable()
                ->toggleable(),
            TextColumn::make('status')
                ->badge()
                ->colors([
                    'warning' => 'pending',
                    'info' => 'processing',
                    'success' => 'completed',
                    'danger' => 'cancelled',
                ]),
            TextColumn::make('total_price')
                ->label('Total')
                ->money('IDR')
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i'),
        ])
        ->filters([])
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
        return [];
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
