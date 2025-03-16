<?php

namespace App\Filament\TenantManager\Resources;

use App\Filament\TenantManager\Resources\TenantResource\Pages;
use App\Filament\TenantManager\Resources\TenantResource\RelationManagers;
use App\Filament\TenantManager\Resources\TenantResource\RelationManagers\UserRelationManager;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make("name")
                    ->label(_("Tenant Name"))
                    ->required(),

                    Forms\Components\TextInput::make("email")
                    ->label(_("Email"))
                    ->required(),

                    Forms\Components\TextInput::make("contact")
                    ->label(_("Contact"))
                    ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                ->label(_("Tenant Name"))
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make("email")
                ->label(_("Email"))
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make("contact")
                ->label(_("Contact"))
                ->searchable()
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
            UserRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
