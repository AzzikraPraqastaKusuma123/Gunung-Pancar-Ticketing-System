<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'type', 'ip_address', 'mac_address', 'status',
        'location', 'stream_url', 'thumbnail_url', 'latitude', 'longitude', 'notes'
    ];
}
