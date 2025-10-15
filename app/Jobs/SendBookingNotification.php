<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBookingNotification implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle()
    {
        $user = $this->booking->user;
        // For the skeleton we will notify only when booking status becomes confirmed.
        if ($this->booking->status === 'confirmed') {
            $user->notify(new BookingConfirmed($this->booking));
        }
        // In real flow, you may send different notifications depending on status.
    }
}
