<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
    ->schema([
        Forms\Components\Section::make(__('Product Details'))
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Product Name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('code')
                    ->label(__('Product Code'))
                    ->required()
                    ->unique('products', 'code')
                    ->maxLength(255),

                Forms\Components\Select::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name')
                    // ->searchable()
                    ->required(),

                Forms\Components\Select::make('unit_id')
                    ->label(__('Unit'))
                    ->relationship('unit', 'key')
                    // ->searchable()
                    ->required(),
            ]),

        Forms\Components\Section::make(__('Stock & Pricing'))
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('quantity')
                    ->label(__('Stock Quantity'))
                    ->numeric()
                    ->default(1)
                    ->required(),

                Forms\Components\TextInput::make('safety_stock')
                    ->label(__('Safety Stock'))
                    ->numeric()
                    ->default(0)
                    ->helperText('Minimum stock before restocking.'),

                Forms\Components\TextInput::make('regular_price')
                    ->label(__('Regular Price'))
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('discounted_price')
                    ->label(__('Discounted Price'))
                    ->numeric()
                    ->nullable(),

                Forms\Components\TextInput::make('in_stock_price')
                    ->label(__('In-Stock Price'))
                    ->numeric()
                    ->default(0),
            ]),

        Forms\Components\Section::make(__('Batch & Expiry'))
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('batch_number')
                    ->label(__('Batch Number'))
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('expiry_date')
                    ->label(__('Expiry Date'))
                    ->nullable(),
            ]),

        Forms\Components\Section::make(__('Additional Information'))
            ->columns(2)
            ->schema([
                Forms\Components\Toggle::make('requires_prescription')
                    ->label(__('Requires Prescription'))
                    ->default(false),

                Forms\Components\TextInput::make('strength')
                    ->label(__('Strength'))
                    ->maxLength(255)
                    ->nullable()
                    ->helperText("e.g., '500mg', '250 IU', etc."),

                Forms\Components\Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(3)
                    ->nullable(),

                Forms\Components\KeyValue::make('data')
                    ->label(__('Additional Data'))
                    ->nullable()
                    ->helperText('Store extra product information as key-value pairs.'),
            ]),
    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('Product Name'))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('code')
                ->label(__('Product Code'))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label(__('Category'))
                ->sortable(),

            Tables\Columns\TextColumn::make('unit.name')
                ->label(__('Unit'))
                ->sortable(),

            Tables\Columns\TextColumn::make('quantity')
                ->label(__('Stock Quantity'))
                ->sortable()
                ->numeric(),

            Tables\Columns\TextColumn::make('regular_price')
                ->label(__('Regular Price'))
                ->money('USD') // Change to your preferred currency
                ->sortable(),

            Tables\Columns\TextColumn::make('discounted_price')
                ->label(__('Discounted Price'))
                ->money('USD')
                ->sortable()
                ->color('danger') // Highlight if there's a discount
                ->default('-'),

            Tables\Columns\TextColumn::make('in_stock_price')
                ->label(__('In-Stock Price'))
                ->money('USD')
                ->sortable(),

            Tables\Columns\TextColumn::make('batch_number')
                ->label(__('Batch Number'))
                ->sortable()
                ->default('-'),

            Tables\Columns\TextColumn::make('expiry_date')
                ->label(__('Expiry Date'))
                ->date()
                ->sortable(),

            Tables\Columns\IconColumn::make('requires_prescription')
                ->label(__('Prescription'))
                ->boolean(),

            Tables\Columns\TextColumn::make('strength')
                ->label(__('Strength'))
                ->default('-'),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Added On'))
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('category_id')
                ->label(__('Category'))
                ->relationship('category', 'name'),

            Tables\Filters\TernaryFilter::make('requires_prescription')
                ->label(__('Requires Prescription')),

            Tables\Filters\Filter::make('expired')
                ->label(__('Expired Products'))
                ->query(fn ($query) => $query->whereDate('expiry_date', '<', now())),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
