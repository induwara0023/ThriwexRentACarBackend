<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_no',
        'model',
        'service_type',
        'type',
        'transmission',
        'current_km',
        'next_service_km',
        'insurance_expiry',
        'license_expiry',
        'status',
        'daily_rate',
        'km_limit_per_day',
        'extra_km_rate',
        'image_path',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'extra_km_rate' => 'decimal:2',
        'current_km' => 'integer',
        'next_service_km' => 'integer',
        'km_limit_per_day' => 'integer',
        'insurance_expiry' => 'date',
        'license_expiry' => 'date',
    ];

    /**
     * Get the bookings for the vehicle.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
