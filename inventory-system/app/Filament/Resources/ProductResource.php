<?php

namespace App\Filament\Resources;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
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
            ->schema(self::getProductForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getProductTableColumns())
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('sell')
                    ->label('Sell')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->url(fn ($record) => route('filament.admin.resources.products.sell', [
                        'record' => $record->getKey(),
                        'tenant' => filament()->getTenant()->getKey(),
                    ]))
                    ->openUrlInNewTab(false), 
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
            // Define any relations here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'sell' => Pages\SellProduct::route('/{record}/sell'),
        ];
    }

    // Form schema method
    public static function getProductForm()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label(__('Product Name')),

            Forms\Components\TextInput::make('code')
                ->label(__('Product Code'))
                ->nullable(),

            Forms\Components\Select::make('category_id')
                ->label(__('Category'))
                ->relationship('category', 'name')
                ->required(),

            Forms\Components\Select::make('unit_id')
                ->label(__('Unit'))
                ->relationship('unit', 'name')
                ->nullable(),

                Forms\Components\FileUpload::make('image')
                ->label('Product Image')
                ->disk('public')
                ->directory('products')
                ->visibility('public')
                ->image()  // Ensures the file is an image
                ->nullable(),


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

                Tables\Columns\ImageColumn::make('image')
                ->label('Image')
                ->square()
                ->size(50)
                ->getStateUsing(fn($record) => url('storage/' . $record->image)),


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
                ->label('Stock Quantity')
                ->sortable(),
                // ->state(function ($record) {
                //     return $record->stocks->sum('quantity'); // Sum the quantities from all stock batches
                // }),

            Tables\Columns\TextColumn::make('regular_price')
                ->label(__('Price'))
                ->sortable(),

            Tables\Columns\TextColumn::make('discounted_price')
                ->label(__('Discounted Price'))
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created')
                ->since() // ➜ shows "2 hours ago", "3 days ago", etc.
                ->sortable(),

        ];
    }
    public static function query()
    {
        return Product::with('stocks'); // Eager load the 'stocks' relationship
    }
}
