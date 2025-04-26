<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Filament\Resources\SaleResource\Pages;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    public static function getNavigationLabel(): string
    {
        return _('Manage Sales');
    }

    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Select product
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->required(),

                // Select customer with option to create a new customer
                Forms\Components\Select::make('customer_id')
                ->label('Customer')
                ->relationship(
                    name: 'customer',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn ($query) => $query->orderBy('name')
                )
                ->required()
                // ->searchable()
                ->createOptionForm(CustomerResource::getCustomerFormSchema()),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required(),

                // Total price input
                Forms\Components\TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->required(),

                // Date picker for the date of sale
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

                // Display quantity sold in the table
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity Sold')
                    ->sortable(),

                // Display total price in the table
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->sortable(),

                // Display the date when the sale occurred
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
