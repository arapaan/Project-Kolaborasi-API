<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PageSection;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PageSectionResource\Pages;
use App\Filament\Resources\PageSectionResource\RelationManagers;
use App\Models\Page;
use Filament\Forms\Components\Hidden;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('page_id')
                    ->label('Halaman')
                    ->relationship('page', 'name')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Ambil data page dari ID yang baru dipilih
                        $page = \App\Models\Page::find($state);
                        $set('section_key', $page?->slug); // isi otomatis field section_key
                    })
                    ->required(),
                TextInput::make('section_key')
                    ->label('KEY')
                    ->readOnly(), 
                TextInput::make('order')
                    ->label('Order')
                    ->required(),

                Forms\Components\Group::make()
                ->schema(function ($get) {
                    $pageId = $get('page_id');
                    $page = Page::find($pageId);
                    $pageName = $page?->slug;                    

                    // HOME PAGE
                    if ($pageName == 'home') {
                        return [
                            TextInput::make('content.title1')->label('Title 1'),
                            TextInput::make('content.title2')->label('Title 2'),
                            TextInput::make('content.title3')->label('Title 3'),
                            FileUpload::make('file_path')->label('Image')->directory('public/images'),                                                                                
                            TextInput::make('content.subtitle')->label('Subtitle'),
                        ];
                    }

                    // ABOUT PAGE
                    if ($pageName == 'about') {
                        return [
                            TextInput::make('content.title1')->label('Title 1'),
                            Textarea::make('content.description')->label('Description'),
                            FileUpload::make('file_path')->label('Image')->directory('public/images'),
                        ];
                    }

                    // DISCOUNT PAGE
                    if ($pageName == 'discount') {
                        return [
                            TextInput::make('content.title1')->label('Title 1'),
                            Textarea::make('content.description')->label('Description'),                            
                        ];
                    }

                    // Default (jika page belum dipilih)
                    return [
                        Forms\Components\Placeholder::make('note')
                            ->content('Pilih halaman terlebih dahulu untuk melihat field.'),
                    ];
                })
                ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPageSections::route('/'),
            'create' => Pages\CreatePageSection::route('/create'),
            'edit' => Pages\EditPageSection::route('/{record}/edit'),
        ];
    }
}
