<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 
        'ticket_id', 
        'quantity', 
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
