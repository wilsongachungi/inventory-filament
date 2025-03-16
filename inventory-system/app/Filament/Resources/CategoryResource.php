<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function getNavigationLabel(): string
    {
        return _("Manage Category");
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
                    ->label(__("Category Name"))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make("description")
                    ->label(__("Description"))
                    ->nullable()
                    ->maxLength(500)
                    ->columnSpanFull(), // Full width for better UI

                Forms\Components\KeyValue::make("data")
                    ->label(__("Additional Data"))
                    ->nullable()
                    ->columnSpanFull(), // Spans the full width
            ]),
    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
    ->columns([
        Tables\Columns\TextColumn::make("name")
            ->label(__("Category Name"))
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make("description")
            ->label(__("Description"))
            ->limit(50) // Limits text length in table view
            ->wrap(),

        Tables\Columns\TextColumn::make("created_at")
            ->label(__("Created At"))
            ->dateTime()
            ->sortable(),

        Tables\Columns\TextColumn::make("updated_at")
            ->label(__("Last Updated"))
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
