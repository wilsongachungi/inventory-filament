<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Models\Sale;
use App\Models\Product;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SaleResource;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $product = Product::find($data['product_id']);

        if ($product->quantity >= $data['quantity']) {
            // Decrement the stock quantity
            $product->decrement('quantity', $data['quantity']);
        } else {
            // Handle case when there's not enough stock
            throw new \Exception('Not enough stock to complete the sale');
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

