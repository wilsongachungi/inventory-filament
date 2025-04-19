<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use App\Models\Purchase;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\PurchaseResource;

class Invoice extends Page
{
    protected static string $resource = PurchaseResource::class;

    public $record;

    public $purchase;

    public function mount($record)
    {
        $this->record = $record;

        $this->purchase = Purchase::with(['provider','products'])->find($record);
    }

    public function getHeaderActions() :array
    {
        return [
            Action::make('print')
            ->label(__("Print"))
            ->icon('heroicon-o-printer')
            ->requiresConfirmation()
            ->url(fn () => route('PRINT.PURCHASE_INVOICE', ['id' => $this->record]))
    ->openUrlInNewTab()
        ];
    }

    protected static string $view = 'filament.resources.purchase-resource.pages.invoice';
}
