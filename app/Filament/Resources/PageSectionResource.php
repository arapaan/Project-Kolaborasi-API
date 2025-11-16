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
use Filament\Tables\Columns\TextColumn;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Templates';

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
                        $page = \App\Models\Page::find($state);
                        $set('section_key', $page?->slug);
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
                            FileUpload::make('file_path')->label('Image')->directory('images')->visibility('public')->getUploadedFileNameForStorageUsing(fn ($file) => $file->getClientOriginalName()),                                                                                
                            TextInput::make('content.subtitle')->label('Subtitle'),
                        ];
                    }

                    // ABOUT PAGE
                    if ($pageName == 'about') {
                        return [
                            TextInput::make('content.title1')->label('Title1'),
                            TextInput::make('content.title2')->label('Title2'),
                            TextInput::make('content.title3')->label('Title3'),
                            TextInput::make('content.title4')->label('Title4'),
                            Textarea::make('content.description1')->label('Description1'),                            
                            Textarea::make('content.description2')->label('Description2'),
                            Textarea::make('content.description3')->label('Description3'),
                            Textarea::make('content.description4')->label('Description4'),                        
                            FileUpload::make('file_path')->label('Image')->directory('images'),
                        ];
                    }

                    // DISCOUNT PAGE
                    if ($pageName == 'discount') {
                        return [
                            TextInput::make('content.title')->label('Title'),
                            Textarea::make('content.description')->label('Description'),                            
                        ];
                    }

                    // MENU PAGE
                    if ($pageName == 'menu') {
                        return [
                            TextInput::make('content.title')->label('Title'),
                            Textarea::make('content.description')->label('Description'),
                        ];
                    }

                    // FEEDBACK PAGE
                    if ($pageName == 'feedback') {
                        return [
                            TextInput::make('content.title')->label('Title'),
                            TextInput::make('content.subtitle1')->label('subtitle'),
                            TextInput::make('content.subtitle2')->label('subtitle2'),
                            TextInput::make('content.subtitle3')->label('subtitle3'),
                            FileUpload::make('file_path')->label('Image')->directory('images'),
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
                TextColumn::make('page.name')
                    ->label('Page')
                    ->sortable()->searchable(),
                TextColumn::make('section_key')
                    ->label('Key')
                    ->sortable()->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->sortable()->searchable(),
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
