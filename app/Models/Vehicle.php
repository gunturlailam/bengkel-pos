<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['customer_id', 'plate_number', 'brand', 'model', 'type', 'color', 'year'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
