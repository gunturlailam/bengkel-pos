<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = ['category_id', 'sku', 'name', 'buy_price', 'sell_price', 'stock', 'min_stock', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
