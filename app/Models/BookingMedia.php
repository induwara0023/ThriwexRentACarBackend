<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingMedia extends Model
{
    use HasFactory;

    protected $table = 'booking_media';

    protected $fillable = [
        'booking_id',
        'type',
        'file_path',
    ];

    /**
     * Get the booking that owns the media.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
