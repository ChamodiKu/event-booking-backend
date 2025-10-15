<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Booking;

class PreventDoubleBooking
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $ticketId = $request->route('id') ?? $request->input('ticket_id');

        if (!$user || !$ticketId) {
            return $next($request);
        }

        $exists = Booking::where('user_id', $user->id)
                    ->where('ticket_id', $ticketId)
                    ->whereIn('status', ['pending','confirmed'])
                    ->exists();

        if ($exists) {
            return response()
                 ->json(['message' => 'You already have a pending/confirmed booking for this ticket'], 422);
        }

        return $next($request);
    }
}
