<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrackingHistory;
use App\Models\DeliveryItem; // Import the DeliveryItem model

class Delivery extends Model
{
    protected $fillable = [
        'status',
        'notes',
    ];

    public function trackingHistory()
    {
        return $this->hasMany(TrackingHistory::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }
}