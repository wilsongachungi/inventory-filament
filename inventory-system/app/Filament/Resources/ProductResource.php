<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getNavigationLabel(): string
    {
        return _("Manage Products");
    }

    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getProductForm()); // Use getProductForm for the form schema
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getProductTableColumns()); // Use a new function for table columns
    }

    public static function getRelations(): array
    {
        return [
            // Define any relations here if needed
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

    // Form schema method
    public static function getProductForm()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label(__('Product Name'))
                ,

            Forms\Components\TextInput::make('code')
                ->label(__('Product Code'))
                ->required(),

            Forms\Components\Select::make('category_id')
                ->label(__('Category'))
                ->relationship('category', 'name') // Relationship for category
                ->required(),

            Forms\Components\Select::make('unit_id')
                ->label(__('Unit'))
                ->relationship('unit', 'name') // Relationship for unit
                ->required(),

            Forms\Components\TextInput::make('quantity')
                ->label(__('Stock Quantity'))
                ->numeric()
                ->default(1)
                ->required(),

            Forms\Components\TextInput::make('regular_price')
                ->label(__('Regular Price'))
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('discounted_price')
                ->label(__('Discounted Price'))
                ->numeric()
                ->nullable(),
        ];
    }

    // Table column method
    public static function getProductTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('Product Name'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('code')
                ->label(__('Product Code'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label(__('Category'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('unit.name')
                ->label(__('Unit'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('quantity')
                ->label(__('Stock Quantity'))
                ->sortable(),

            Tables\Columns\TextColumn::make('regular_price')
                ->label(__('Price'))
                ->sortable(),

            Tables\Columns\TextColumn::make('discounted_price')
                ->label(__('Discounted Price'))
                ->sortable(),
        ];
    }
}
