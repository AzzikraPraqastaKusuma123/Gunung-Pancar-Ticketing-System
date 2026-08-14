<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'event_date' => 'date',
        'last_follow_up' => 'datetime',
        'next_follow_up' => 'datetime',
    ];

    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class);
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(LetterOfAgreement::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
