<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'photo_path',
        'order',
    ];

    /**
     * Get the product that owns the photo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL for the photo
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->photo_path);
    }
}
