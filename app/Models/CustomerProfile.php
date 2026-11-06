<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'preferred_delivery_slot_id',
        'total_due_balance',
        'total_empty_cans_held',
        'onboarding_completed',
    ];

    protected $casts = [
        'total_due_balance' => 'decimal:2',
        'total_empty_cans_held' => 'integer',
        'onboarding_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preferredSlot()
    {
        return $this->belongsTo(DeliverySlot::class, 'preferred_delivery_slot_id');
    }
}
