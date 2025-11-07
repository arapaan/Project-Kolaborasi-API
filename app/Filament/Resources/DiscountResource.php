<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Discount;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\DiscountResource\Pages;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-percent-badge';
    protected static ?string $navigationGroup = 'Products';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('employee');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('business_id')
                ->relationship('business', 'name_company')
                ->required(),

            TextInput::make('discount')
                ->integer()
                ->required(),      
                
            DatePicker::make('expires_date')
                ->date()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Id Discount'),
                TextColumn::make('discount')
                    ->label('Potongan'),
                TextColumn::make('expires_date')
                    ->label('Tanggal Kadaluwarsa'),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
