<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $fillable = ['work_order_id', 'part_id', 'service_id', 'name', 'qty', 'price', 'subtotal'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
