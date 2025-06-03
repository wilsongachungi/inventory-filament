<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Stock;
use Filament\Tables;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Resources\ResourceForm;
use Filament\Resources\ResourceTable;
use App\Filament\Resources\StockResource\Pages;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    public static function getNavigationLabel(): string
    {
        return 'Manage Stock';
    }

    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('cost_price')
                    ->label('Cost Price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product Name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Cost Price')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added On')
                    ->since()
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}



