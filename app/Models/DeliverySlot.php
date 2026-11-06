<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySlot extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'max_daily_capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_daily_capacity' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
