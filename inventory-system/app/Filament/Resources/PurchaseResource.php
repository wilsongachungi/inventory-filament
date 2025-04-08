<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use App\Models\Category;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Filament\Resources\PurchaseResource\RelationManagers\ProductRelationManager;
use App\Filament\Resources\PurchaseResource\RelationManagers\ProductsRelationManager;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationGroup = 'Transaction';


    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function getNavigationLabel(): string
    {
        return _("Purchase");
    }

    public static function form(Form $form): Form
  {
    return $form
    ->schema([
        // Provider Details
        Forms\Components\Section::make('Provider Details')
            ->columns(3)
            ->schema([
                Forms\Components\Select::make('provider_id')
                    ->relationship('provider', 'name')
                    ->createOptionForm(function(){

                        $tenantField = [
                            Forms\Components\Hidden::make('tenant_id')
                            ->default(Filament::getTenant()->id)
                            ->label(__('Tenant'))
                        ];
                    return array_merge(CustomerResource::getCustomerFormSchema(),$tenantField);
             })
                    ->required(),

                Forms\Components\TextInput::make('invoice_no')
                    ->label('Invoice Number')
                    ->required(),

                Forms\Components\DatePicker::make('purchase_date')
                    ->label('Purchase Date')
                    ->required(),
            ]),

      // Products Details Section
Forms\Components\Section::make(__('Products Details'))
->schema([
    Forms\Components\Repeater::make('products')
        ->schema([
            Forms\Components\Grid::make(4) // Ensures proper alignment in 4 columns
                ->schema([
                    Forms\Components\Select::make('product_id')
                        ->label(__('Product'))
                        ->options(Product::pluck('name', 'id')->toArray())
                        ->searchable()
                        ->required()
                        ->columnSpan(1), // Align properly

                    Forms\Components\TextInput::make('quantity')
                        ->label(__('Stock Quantity'))
                        ->reactive()
                        ->numeric()
                        ->default(1)
                        ->debounce(500)
                        ->afterStateUpdated(fn (Callable $get, Set $set) => self::updateFormData($get, $set))
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('price')
                        ->label(__('Price'))
                        ->reactive()
                        ->numeric()
                        ->required()
                        ->debounce(500)
                        ->afterStateUpdated(fn (Callable $get, Set $set) => self::updateFormData($get, $set))
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('total')
                        ->label(__('Total'))
                        ->numeric()
                        ->disabled()
                        ->columnSpan(1),
                ]),
        ])
        ->columns(1), // Keep each row inside the repeater aligned properly
            ]),
// Prevent manual editing

                                // Forms\Components\TextInput::make('safety_stock')
                                //     ->label(__('Safety Stock'))
                                //     ->numeric()
                                //     ->default(0)
                                //     ->helperText('Minimum stock before restocking.'),



            Forms\Components\Section::make(__('Batch & Expiry'))
            ->columns(3)
            ->schema([
                Forms\Components\TextInput::make('sub_total')
                ->label(__('Sub Total'))
                ->numeric()
                ->required()
                ->reactive(),

                Forms\Components\TextInput::make('discounted_price')
                ->label(__('Discounted Price'))
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(function(callable $get, Set $set){
                    $discount = $get('discounted_price') ?? 0;
                    $totalAmount = $get('sub_total') ?? 0;
                    $set('net_total',$totalAmount - $discount);
                }),

                Forms\Components\TextInput::make('net_total')
                ->label(__('Net Total'))
                ->numeric()
                ->required(),
            ]),
    //     // Batch & Expiry
    //     Forms\Components\Section::make(__('Batch & Expiry'))
    //         ->columns(2)
    //         ->schema([
    //             Forms\Components\TextInput::make('batch_number')
    //                 ->label(__('Batch Number'))
    //                 ->nullable()
    //                 ->maxLength(255),

    //             Forms\Components\DatePicker::make('expiry_date')
    //                 ->label(__('Expiry Date'))
    //                 ->nullable(),
    //         ]),

    //     // Additional Information
    //     Forms\Components\Section::make(__('Additional Information'))
    //         ->columns(2)
    //         ->schema([
    //             Forms\Components\Toggle::make('requires_prescription')
    //                 ->label(__('Requires Prescription'))
    //                 ->default(false),

    //             Forms\Components\TextInput::make('strength')
    //                 ->label(__('Strength'))
    //                 ->maxLength(255)
    //                 ->nullable()
    //                 ->helperText("e.g., '500mg', '250 IU', etc."),

    //             Forms\Components\Textarea::make('description')
    //                 ->label(__('Description'))
    //                 ->rows(3)
    //                 ->nullable(),

    //             Forms\Components\KeyValue::make('data')
    //                 ->label(__('Additional Data'))
    //                 ->nullable()
    //                 ->helperText('Store extra product information as key-value pairs.'),
    //         ]),
     ]);

  }

  public static function updateFormData($get,$set)
  {
    $FormData = $get('../../');
    $allProducts = $FormData['products'] ?? [];
    $grandTotal = 0;
    foreach($allProducts as $product)
    {
        $price = $product['price'] ?? 0;
        $quantity = $product['quantity'] ?? 0;
        $total = $price * $quantity;
        $grandTotal += $total;
    }
    $price =  $get('price');
    $quantity = $get('quantity');
    $total = $price * $quantity;
    $set('total', $total);
    $set('../../sub_total',$grandTotal);

    $discount = $get('../../discount');
    $set('../../net_total',$grandTotal - $discount);
  }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_no')
                ->label(__('Invoice No')),

            Tables\Columns\TextColumn::make('provider.name')
                ->label(__('Provider')),


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

            Tables\Columns\TextColumn::make('price')
                ->label(__('Price'))
                ->sortable(),

            Tables\Columns\TextColumn::make('discounted_price')
                ->label(__('Discounted Price'))
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("view_invoice")
                ->label(__("View Invoice"))
                ->icon(__("heroicon-o-document"))
                ->url(fn($record)=>self::getUrl("invoice",['record'=> $record->id]))
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
            ProductsRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
            'invoice' => Pages\Invoice::route('/{record}/invoice')
        ];
    }
}
