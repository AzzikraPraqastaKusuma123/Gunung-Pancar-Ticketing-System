<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_name',
        'coordinates',
        'notes',
        'status',
        'photo_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
