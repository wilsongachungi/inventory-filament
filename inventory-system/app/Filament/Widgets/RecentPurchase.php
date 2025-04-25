<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\PurchaseResource;
use Filament\Tables\Actions\Action; // ✅ Correct one
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPurchase extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Purchase::latest()
            )
            ->columns([
                TextColumn::make('provider.name'),
                TextColumn::make('invoice_no')
                    ->searchable(),
            ])
            ->actions([
                Action::make('view_invoice')
                    ->label('View Invoice')
                    ->url(function ($record) {
                        return PurchaseResource::getUrl('invoice', [
                            'tenant' => Filament::getTenant()->id,
                            'record' => $record->id,
                        ]);
                    })
                    ->openUrlInNewTab()
            ]);
    }
}
