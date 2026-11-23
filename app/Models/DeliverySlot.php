<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function isPassedForDate($dateString): bool
    {
        $date = Carbon::parse($dateString)->toDateString();
        $today = Carbon::today()->toDateString();

        if ($date !== $today) {
            return false;
        }

        $currentTime = Carbon::now()->format('H:i:s');
        return $this->start_time <= $currentTime;
    }
}
