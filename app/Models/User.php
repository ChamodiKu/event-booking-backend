<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasFactory;
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'role'
    ];
    protected $hidden = [
        'password', 
        'remember_token'
    ];
    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
