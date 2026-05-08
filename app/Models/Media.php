<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    // Specifying the table name as 'media' (Laravel usually plurals 'Medium' to 'Media', but explicit is better)
    protected $table = 'media';

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
