<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable =  ['name','email','contact','data'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }


    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
}
