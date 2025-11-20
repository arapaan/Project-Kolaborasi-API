<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Business;
use App\Models\Category;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Products';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('employee');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('quantity')
                    ->required(),
                TextInput::make('price'),

                // Tambahkan field upload / URL PNG di sini 👇
                FileUpload::make('url_png')
                    ->label('Product Image (PNG)')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->directory('images')
                    ->nullable()
                    ->columnSpanFull(),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('business_id')
                    ->label('Business')
                    ->relationship('business', 'name_company')
                    ->required(),
                Select::make('discount_id')
                    ->label('Discount')
                    ->relationship('discount', 'discount'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Stok Product')
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                // Tampilkan gambar PNG di tabel
                ImageColumn::make('url_png')
                    ->label('Image')
                    ->square(),
                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
