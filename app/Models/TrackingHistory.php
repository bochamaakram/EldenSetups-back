<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingHistory extends Model
{
    protected $fillable = [
        'delivery_id',
        'status',
        'location',
        'timestamp',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}