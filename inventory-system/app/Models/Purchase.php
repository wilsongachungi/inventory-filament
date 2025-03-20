<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    protected $casts = [
        "data" => "json"
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Purchase belongs to a provider
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    // Purchase has many products via the purchase_products pivot table
    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_products')
                    ->withPivot('price', 'quantity', 'data');  // Access pivot data like price, quantity, and data
    }
}

