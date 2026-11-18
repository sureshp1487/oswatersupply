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
        'latitude',
        'longitude',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAddressAttribute(): string
    {
        return "{$this->door_number}, {$this->house_or_building}, {$this->street}, {$this->area_locality}, {$this->city} - {$this->pincode}";
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/dir/?api=1&destination={$this->latitude},{$this->longitude}";
        }
        $query = urlencode($this->formatted_address);
        return "https://www.google.com/maps/dir/?api=1&destination={$query}";
    }
}
