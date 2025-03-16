<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Filament\Resources\UnitResource\RelationManagers;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    public static function getNavigationLabel(): string
    {
        return _("Manage Units");
    }

    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make("name")
                    ->label(__("Unit Name"))
                    ->required()
                    ->maxLength(255)
                    ->helperText("Full unit name, e.g., 'Tablet', 'Bottle', 'Ampoule'."),

                Forms\Components\TextInput::make("key")
                    ->label(__("Short Unit"))
                    ->required()
                    ->unique(ignoreRecord:true) // Ensures uniqueness in the database
                    ->maxLength(255)
                    ->helperText("Enter a unique short code, e.g., 'TAB' for Tablet, 'BTL' for Bottle."),


                    Forms\Components\KeyValue::make("data")
                        ->label(__("Additional Data"))
                        ->nullable(),
                ]),
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make("name")
                ->label(__("Unit Name"))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("key")
                ->label(__("Short Code"))
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make("created_at")
                ->label(__("Created At"))
                ->dateTime()
                ->sortable(),
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
