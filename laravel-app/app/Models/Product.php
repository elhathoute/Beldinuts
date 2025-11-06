<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_gram_retail',
        'price_per_gram_wholesale',
        'stock',
    ];

    protected $casts = [
        'price_per_gram_retail' => 'decimal:2',
        'price_per_gram_wholesale' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Get product orders
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get product reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get product photos
     */
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class)->orderBy('order');
    }
}
