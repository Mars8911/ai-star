<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiStar extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'unique_id',
        'video_path',
        'audio_path',
        'public_price',
        'business_price',
        'introduction',
        'is_active',
    ];

    protected $casts = [
        'public_price' => 'decimal:2',
        'business_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
} 