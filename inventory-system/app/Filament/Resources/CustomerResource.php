<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    
    protected static ?string $model = Customer::class;

    public static function getNavigationLabel(): string
    {
        return _("Customers");
    }

    protected static ?string $navigationGroup = 'Entities';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema(self::getCustomerFormSchema()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getCustomerTableSchema())
            ->filters([
                // Any filters you want
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    // ✅ Reusable Form Schema
    public static function getCustomerFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make("name")
                ->label(_("Name"))
                ->required(),

            Forms\Components\TextInput::make("email")
                ->label(_("Email"))
                ->email(),

            Forms\Components\TextInput::make("contact")
                ->label(_("Contact"))
                ->required(),

            Forms\Components\TextInput::make("address")
                ->label(_("Address")),

            Forms\Components\KeyValue::make("data")
                ->label(_("Extra Details")),
        ];
    }

    // ✅ New: Reusable Table Schema
    public static function getCustomerTableSchema(): array
    {
        return [
            Tables\Columns\TextColumn::make("name")
                ->label(_("Customer Name"))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("email")
                ->label(_("Email"))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("contact")
                ->label(_("Contact"))
                ->searchable(),

            Tables\Columns\TextColumn::make("address")
                ->label(_("Address"))
                ->searchable(),
        ];
    }
    public static function getCustomerCreateSchema(): array
{
    return [
        Forms\Components\TextInput::make('name')
            ->label('Name')
            ->required(),

        Forms\Components\TextInput::make('email')
            ->label('Email')
            ->email(),

        Forms\Components\TextInput::make('contact')
            ->label('Contact')
            ->required(),

        Forms\Components\TextInput::make('address')
            ->label('Address'),
    ];
}

}
