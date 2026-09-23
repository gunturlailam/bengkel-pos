<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = ['invoice_number', 'customer_id', 'vehicle_id', 'user_id', 'status', 'subtotal', 'discount', 'total'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }
}
