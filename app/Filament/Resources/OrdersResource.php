<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrdersResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrdersResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\ProductsRelationManager;


class OrdersResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Orders & Notifications';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('employee');
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;
        if (!empty($data['products'])) {
            foreach ($data['products'] as $productData) {
                $price = $productData['price'] ?? 0;
                $qty = $productData['quantity'] ?? 1;
                $total += $price * $qty;
            }
        }

        $data['total_price'] = $total;
        return $data;
    }

    public static function afterCreate($record, array $data): void
    {
        if (!empty($data['products'])) {
            foreach ($data['products'] as $productData) {
                $record->products()->attach($productData['product_id'], [
                    'quantity' => $productData['quantity'],
                ]);
            }
        }
    }

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

            Select::make('status')
                ->options([
                    'order'     => 'Order',
                    'diproses'  => 'Diproses',
                    'selesai'   => 'Selesai',
                ])
                ->required(),

            Repeater::make('products')
                ->label('Daftar Produk Dipesan')
                    ->reactive()
                    ->required()                               
                    ->dehydrated()
                ->schema([
                    Select::make('product_id')
                        ->label('Produk')
                        ->relationship('products', 'name')
                        ->required()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $price = \App\Models\Product::find($state)?->price ?? 0;
                                $set('price', $price);

                                // Hitung subtotal saat produk diganti
                                $quantity = $get('quantity') ?? 1;
                                $set('total_price', $price * $quantity);

                                // Update total luar
                                $set('../../total_price', collect($get('../../products') ?? [])
                                    ->sum('total_price'));
                            }),

                    TextInput::make('price')
                        ->label('Harga Satuan')
                        ->numeric()
                        ->disabled()
                            ->dehydrated(false),

                    TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                            ->minValue(1)
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                                // Hitung subtotal per produk
                                $subtotal = ($get('price') ?? 0) * ($state ?? 0);
                                $set('total_price', $subtotal);

                                // ✅ Update total luar (semua subtotal dijumlah)
                                $set('../../total_price', collect($get('../../products') ?? [])
                                    ->sum('total_price'));
                        }),

                    TextInput::make('total_price')
                        ->label('Subtotal')
                        ->numeric()
                        ->readOnly()
                        ->dehydrated(),
                ])
                    ->mutateDehydratedStateUsing(function ($state) {
                        return collect($state ?? [])
                            ->map(function ($item) {
                                return [
                                    'product_id'   => $item['product_id'] ?? null,
                                    'quantity'     => isset($item['quantity']) ? (int)$item['quantity'] : 1,
                                    'price'        => $item['price'] ?? 0,
                                    'total_price'  => $item['total_price'] ?? 0,
                                ];
                            })
                            ->filter(fn($i) => $i['product_id'] !== null)
                            ->values()
                            ->toArray();
                    })
                ->columns(3)
                ->defaultItems(1)
                    ->createItemButtonLabel('Tambah Produk'),

            TextInput::make('total_price')
                ->label('Total Harga')
                ->numeric()
                ->readOnly()
                ->dehydrated()
                ->default(0),
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
                TextColumn::make('created_by')
                    ->label('Staff')
                    ->searchable()->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
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
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
    return [
        'index' => Pages\ListOrders::route('/'),
        'create' => Pages\CreateOrders::route('/create'),
        'edit' => Pages\EditOrders::route('/{record}/edit'),
        'view' => Pages\ViewTransaction::route('/{record}'),
    ];
    }


}
