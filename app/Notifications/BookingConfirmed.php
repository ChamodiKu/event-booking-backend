<?php
namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmed extends Notification
{
    use Queueable;

    public Booking $booking;
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Booking Confirmed')
                    ->line('Your booking #'.$this->booking->id.' has been confirmed.')
                    ->line('Event: '.$this->booking->ticket->event->title)
                    ->line('Quantity: '.$this->booking->quantity);
    }

    public function toArray($notifiable)
    {
        return ['booking_id'=>$this->booking->id,'status'=>$this->booking->status];
    }
}
