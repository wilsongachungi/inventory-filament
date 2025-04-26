<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'total_price',
        'customer_id',
        'sold_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);  // Assuming you have a Tenant model
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
