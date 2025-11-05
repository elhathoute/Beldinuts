<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'sale_type',
        'quantity_grams',
        'quantity_pieces',
        'unit_price',
    ];

    protected $casts = [
        'quantity_grams' => 'integer',
        'quantity_pieces' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Get order item order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get order item product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
