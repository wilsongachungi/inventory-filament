<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use Filament\Actions;
use App\Models\Purchase;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PurchaseResource;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    public function handleRecordCreation(array $data): Model
    {
        $purchase = Purchase::create([
            'tenant_id' => Filament::getTenant()->id,
            'provider_id' => $data['provider_id'],
            'invoice_no' => $data['invoice_no'],
            'purchase_date' => $data['purchase_date'],
            'total' => (float) $data['sub_total'],
            'discount' => (float) $data['discounted_price'],
        ]);

        $products = $data['products'];
        foreach($products as $product)
        {

            $purchase->products()->create([
                'product_id'    => (int) $product['product_id'],
                'price' => (float) $product['price'],
                'quantity'      => (int) $product['quantity'],
            ]);
        }

        return $purchase;
    }

}
