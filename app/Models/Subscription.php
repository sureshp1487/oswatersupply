<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'subscription_number',
        'user_id',
        'plan_id',
        'product_id',
        'quantity_per_delivery',
        'delivery_slot_id',
        'address_id',
        'address_snapshot',
        'frequency',
        'preferred_weekday',
        'start_date',
        'end_date',
        'next_delivery_date',
        'total_deliveries',
        'completed_deliveries',
        'price_per_can',
        'subtotal',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'status',
        'payment_method',
        'payment_status',
        'special_instructions',
        'cancellation_reason',
        'paused_at',
        'cancelled_at',
    ];

    protected $casts = [
        'address_snapshot' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_delivery_date' => 'date',
        'quantity_per_delivery' => 'integer',
        'total_deliveries' => 'integer',
        'completed_deliveries' => 'integer',
        'price_per_can' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'paused_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function deliverySlot()
    {
        return $this->belongsTo(DeliverySlot::class, 'delivery_slot_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->total_deliveries <= 0) {
            return 0;
        }
        return (int) min(100, round(($this->completed_deliveries / $this->total_deliveries) * 100));
    }
}
