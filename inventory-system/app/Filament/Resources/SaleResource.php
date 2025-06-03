<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use App\Models\Product;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\SaleResource\Pages;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    public static function getNavigationLabel(): string
    {
        return __('Manage Sales');
    }

    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        // When product is changed, reset price choice and unit price
                        $set('price_type', null);
                        $set('unit_price', null);
                    }),

                Forms\Components\Radio::make('price_type')
                    ->label('Choose Price Type')
                    ->options([
                        'regular' => 'Regular Price',
                        'discounted' => 'Discounted Price',
                    ])
                    ->required()
                    ->reactive()
                    ->visible(fn(Forms\Get $get) => $get('product_id')) // Show only if product selected
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $product = Product::find($get('product_id'));
                        if ($product) {
                            if ($state === 'discounted' && $product->discounted_price !== null) {
                                $set('unit_price', $product->discounted_price);
                            } else {
                                $set('unit_price', $product->regular_price);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $quantity = $get('quantity') ?? 0;
                        $set('total_price', $state * $quantity);
                    }),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $unitPrice = $get('unit_price') ?? 0;
                        $set('total_price', $state * $unitPrice);
                    }),

                Forms\Components\TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship(
                        name: 'customer',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query->orderBy('name')
                    )
                    ->required()
                    ->createOptionForm(CustomerResource::getCustomerFormSchema()),

                Forms\Components\DatePicker::make('sold_at')
                    ->label('Sold At')
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display product name in the table
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Price')
                    ->sortable(),

                // Display quantity sold
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity Sold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->sortable(),

                // Display date sold
                Tables\Columns\TextColumn::make('sold_at')
                    ->label('Sold At')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
        ];
    }
}
