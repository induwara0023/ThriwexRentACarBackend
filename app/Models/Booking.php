<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'pickup_datetime',
        'return_datetime',
        'pickup_km',
        'return_km',
        'advance_payment',
        'total_price',
        'security_item_description',
        'status',
    ];

    /**
     * Get the vehicle that was booked.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the customer who made the booking.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the media files for the booking.
     */
    public function media(): HasMany
    {
        return $this->hasMany(BookingMedia::class);
    }
}
