<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }



    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'purchase_products')
            ->withPivot('price', 'quantity', 'data');  
    }

    public static function boot()
{
    parent::boot();

    static::updating(function ($product) {
        if ($product->isDirty('image')) {
            // Delete old image if it exists and a new one is uploaded
            if ($product->getOriginal('image')) {
                Storage::disk('public')->delete($product->getOriginal('image'));
            }
        }
    });
}
}
