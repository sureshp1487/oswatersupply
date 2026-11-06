<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'volume',
        'unit',
        'description',
        'selling_price',
        'stock_quantity',
        'min_order_quantity',
        'is_active',
        'is_delivery_eligible',
        'image',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_order_quantity' => 'integer',
        'is_active' => 'boolean',
        'is_delivery_eligible' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
