<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;


    class Customer extends Model
    {
        protected $casts = [
            "data"=>"json"
        ];
        protected static function booted()
        {
            static::creating(function ($customer) {
                if (blank($customer->tenant_id) && Filament::getTenant()) {
                    $customer->tenant_id = Filament::getTenant()->id;
                }
            });
        }

        protected $fillable = [
            'name', 'email', 'contact', 'address', 'data', 'tenant_id'
        ];

        public function tenant()
        {
            return $this->belongsTo(Tenant::class);
        }

        public function sales()
        {
            return $this->hasMany(Sale::class);
        }
    }



