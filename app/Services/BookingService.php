<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use App\Jobs\SendBookingNotification;

class BookingService
{
    public function createBooking(int $userId, int $ticketId, int $quantity)
    {
        $ticket = Ticket::findOrFail($ticketId);
        if ($ticket->quantity < $quantity) throw new \Exception('Not enough tickets');
        $booking = Booking::create([
            'user_id' => $userId, 
            'ticket_id' => $ticket->id, 
            'quantity' => $quantity, 
            'status' => 'pending'
        ]);
        $ticket->decrement('quantity', $quantity);
        SendBookingNotification::dispatch($booking);
        return $booking;
    }
    public function cancelBooking(Booking $booking)
    {
        if ($booking->status === 'cancelled') return $booking;
        $booking->status = 'cancelled';
        $booking->save();
        $booking->ticket->increment('quantity', $booking->quantity);
        return $booking;
    }
}
