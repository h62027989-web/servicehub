<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        abort_unless($booking->customer_id === auth()->id(), 403);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.show', $booking);
        }

        if ($booking->status === 'cancelled') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Cancelled bookings cannot be paid.');
        }

        $booking->load(['service', 'provider', 'payment']);

        return view('payments.show', compact('booking'));
    }

    public function pay(Request $request, Booking $booking)
    {
        abort_unless($booking->customer_id === auth()->id(), 403);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'This booking is already paid.');
        }

        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Cancelled bookings cannot be paid.');
        }

        $validated = $request->validate([
            'method'       => ['required', 'in:card,cash'],
            'card_name'    => ['required_if:method,card', 'nullable', 'string', 'max:100'],
            'card_number' => ['required_if:method,card', 'nullable', 'string'],
            'expiry'      => ['required_if:method,card', 'nullable', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv'         => ['required_if:method,card', 'nullable', 'digits:3'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'reference' => 'SH-' . strtoupper(Str::random(10)),
                    'amount'    => $booking->amount,
                    'method'    => $validated['method'],
                    'status'    => 'success',
                    'paid_at'   => now(),
                ]
            );

            $booking->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
            ]);
        });

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Payment successful. Booking confirmed.');
    }
}
