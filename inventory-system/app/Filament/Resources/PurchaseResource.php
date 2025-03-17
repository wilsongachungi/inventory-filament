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
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PurchaseResource\RelationManagers;

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
                        return CustomerResource::getCustomerFormSchema();
                    })
                    ->required(),

                Forms\Components\TextInput::make('invoice_no')
                    ->label('Invoice Number')
                    ->required(),

                Forms\Components\DatePicker::make('purchase_date')
                    ->label('Purchase Date')
                    ->required(),
            ]),

        // Products Details
        Forms\Components\Section::make('Products Details')

            ->schema([
                Forms\Components\Repeater::make('products')
                ->columns(4)
                    ->schema([

                        // Stock & Pricing Section
                        Forms\Components\Section::make(__('Stock & Pricing'))
                            ->columns(4)
                            ->schema([
                                Forms\Components\TextInput::make('regular_price')
                                    ->label(__('Price'))
                                    ->reactive()
                                    ->numeric()
                                    ->required()
                                    ->afterStateUpdated(function (callable $get, Set $set) {
                                        $price = (float) $get('regular_price');
                                        $quantity = (int) $get('quantity');
                                        $set('total', $price * $quantity);
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('Stock Quantity'))
                                    ->reactive()
                                    ->numeric()
                                    ->default(1)
                                    ->afterStateUpdated(function (callable $get, Set $set) {
                                        $price = (float) $get('regular_price');
                                        $quantity = (int) $get('quantity');
                                        $set('total', $price * $quantity);
                                    }),

                                Forms\Components\TextInput::make('total')
                                    ->label(__('Total'))
                                    ->numeric()
                                    ->disabled(), // Prevent manual editing

                                Forms\Components\TextInput::make('safety_stock')
                                    ->label(__('Safety Stock'))
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Minimum stock before restocking.'),

                                Forms\Components\TextInput::make('discounted_price')
                                    ->label(__('Discounted Price'))
                                    ->numeric()
                                    ->nullable(),
                            ]),
                    ]),
            ]),

        // Batch & Expiry
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

        // Additional Information
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
