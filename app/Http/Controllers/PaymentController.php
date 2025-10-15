<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $payments) {}
    public function pay(Request $request, $booking_id)
    {
        try {
            $booking = Booking::where('user_id', $request->user()->id)->findOrFail($booking_id);
            if ($booking->status !== 'pending') return $this->errorResponse('Cannot pay for this booking', null, 422);
            $amount = $booking->ticket->price * $booking->quantity;
            $result = $this->payments->process((float)$amount);
            $payment = Payment::create([
                'booking_id' => $booking->id, 
                'amount' => $amount, 
                'status' => $result['status']
            ]);
            if ($result['status'] === 'success') {
                $booking->status = 'confirmed';
                $booking->save();
            } else {
                $booking->ticket->increment('quantity', $booking->quantity);
            }
            return $this->successResponse($payment, 'Payment processed');
        } catch (\Exception $e) {
            Log::error('PaymentController@pay | ' . $e->getMessage());
            return $this->errorResponse('Payment failed');
        }
    }
    public function show($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            return $this->successResponse($payment);
        } catch (\Exception $e) {
            Log::error('PaymentController@show | ' . $e->getMessage());
            return $this->errorResponse('Payment not found', null, 404);
        }
    }
}
