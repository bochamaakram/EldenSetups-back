<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'catégorie',
        'rating',
        'stock',
    ];

    public static function getFeaturedProducts()
    {
        return self::where('featured', true)->get();
    }
    public function scopeFeatured($query)
{
    return $query->where('featured', true);
}
}
