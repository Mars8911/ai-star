<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'ai_star_id',
        'amount',
        'type',
        'status',
        'payment_method',
        'payment_status',
        'custom_content',
        'language',
        'scene_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function aiStar()
    {
        return $this->belongsTo(AiStar::class);
    }
} 