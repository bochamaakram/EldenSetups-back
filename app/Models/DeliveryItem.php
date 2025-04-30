<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    protected $fillable = [
        'delivery_id',
        'product_name',
        'quantity',
        'price',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}