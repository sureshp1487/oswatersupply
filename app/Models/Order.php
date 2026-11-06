<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'address_snapshot',
        'expected_delivery_date',
        'delivery_slot_id',
        'order_status',
        'payment_method',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'delivery_instructions',
        'internal_notes',
        'delivered_at',
    ];

    protected $casts = [
        'address_snapshot' => 'array',
        'expected_delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function slot()
    {
        return $this->belongsTo(DeliverySlot::class, 'delivery_slot_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function deliveryAssignment()
    {
        return $this->hasOne(DeliveryAssignment::class)->latestOfMany();
    }
}
