<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_email',
        'contact_person',
        'contact_phone',
        'company_id',
        'business_address',
        'establishment_date',
    ];

    protected $casts = [
        'establishment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 