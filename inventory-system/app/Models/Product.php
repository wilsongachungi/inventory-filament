<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        "data" => "json"
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Define the many-to-many relationship with Purchase
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'purchase_products')
                    ->withPivot('price', 'quantity', 'data');  // Access pivot data like price, quantity, and additional data
    }
}
