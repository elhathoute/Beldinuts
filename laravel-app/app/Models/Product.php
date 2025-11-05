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
        'price_per_gram',
        'retail_price',
        'wholesale_price',
        'weight_per_piece',
        'unit',
        'stock',
        'image',
    ];

    protected $casts = [
        'price_per_gram' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'weight_per_piece' => 'integer',
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
}
