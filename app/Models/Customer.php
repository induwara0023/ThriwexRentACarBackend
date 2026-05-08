<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Booking;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nic_no',
        'name',
        'phone',
        'address',
        'status',
        'nic_front',
        'nic_back',
        'license_front',
        'license_back',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the bookings for the customer.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
