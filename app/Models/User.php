<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'occupation',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // courses function to get the courses of the user
    public function courses(){
        return $this->belongsToMany(Course::class, 'course_students');
    }

    // cek langganan
    public function subscribe_transactions() {
        return $this->hasMany(SubscribeTransaction::class);
    }

    // Reusable function to check if user has active subscription
    public function hasActiveSubscription() {
        return $this->subscribe_transactions()
        ->where('is_paid', true)
        ->latest('updated_at')
        ->first();

        if(!$latestSubscription) {
            return false;
        }

        // Carbon adalah class yang digunakan untuk mengelola tanggal dan waktu
        // Carbon::parse ini untuk mengubah string menjadi date
        $SubscriptionEndDate = Carbon::parse($latestSubscription->subscription_start_date)->addMonths(1);
        return Carbon::now()->lessThanOrEqualTo($SubscriptionEndDate); // true == masih berlangganan
    }

}
