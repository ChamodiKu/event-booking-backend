<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;
use App\Jobs\SendBookingNotification;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function store(Request $request, $ticket_id)
    {
        $data = $request->validate(['quantity'=>'required|integer|min:1']);
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->quantity < $data['quantity']) {
                return response()->json(['message'=>'Not enough tickets'], 422);
            }
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'ticket_id' => $ticket->id,
                'quantity' => $data['quantity'],
                'status' => 'pending'
            ]);
            // reduce quantity (simple)
            $ticket->decrement('quantity', $data['quantity']);
            // dispatch notification job (simulated)
            SendBookingNotification::dispatch($booking);
            return response()->json($booking, 201);
        } catch (\Exception $e) {
            Log::error(now() . ' | BookingController@store | ' . $e->getMessage());
            return response()->json(['message'=>'Failed to create booking'], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $bookings = $request->user()->bookings()->with('ticket.event')->get();
            return response()->json($bookings);
        } catch (\Exception $e) {
            Log::error(now() . ' | BookingController@index | ' . $e->getMessage());
            return response()->json(['message'=>'Failed to fetch bookings'], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);
            if ($booking->status === 'cancelled') {
                return response()->json(['message'=>'Already cancelled'], 422);
            }
            $booking->status = 'cancelled';
            $booking->save();
            // return tickets back
            $booking->ticket->increment('quantity', $booking->quantity);
            return response()->json(['message'=>'Cancelled']);
        } catch (\Exception $e) {
            Log::error(now() . ' | BookingController@cancel | ' . $e->getMessage());
            return response()->json(['message'=>'Failed to cancel booking'], 500);
        }
    }
}
