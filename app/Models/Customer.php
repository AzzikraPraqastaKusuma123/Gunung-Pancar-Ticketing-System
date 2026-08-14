<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'tags' => 'array',
        'first_interaction_at' => 'datetime',
        'last_interaction_at' => 'datetime',
        'is_group' => 'boolean',
    ];

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
