<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'nationality',
        'national_id',
        'phone',
        'gender',
        'birth_date',
        'occupation',
        'language',
        'type',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'is_verified' => 'boolean',
    ];

    public function businessProfile()
    {
        return $this->hasOne(BusinessProfile::class);
    }

    public function aiStars()
    {
        return $this->hasMany(AiStar::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
