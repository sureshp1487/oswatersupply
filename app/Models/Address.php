<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'house_or_building',
        'door_number',
        'street',
        'area_locality',
        'city',
        'pincode',
        'delivery_instructions',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAddressAttribute(): string
    {
        return "{$this->door_number}, {$this->house_or_building}, {$this->street}, {$this->area_locality}, {$this->city} - {$this->pincode}";
    }
}
