@extends('layouts.app')

@section('title', 'Payment - ServiceHub')
@section('heading', 'Complete Payment')

@section('content')
<div class="payment-page">
    <div class="payment-summary card reveal">
        <div class="payment-brand">
            <span class="payment-icon">✓</span>
            <div>
                <small class="eyebrow">SECURE CHECKOUT</small>
                <h2>{{ $booking->service->name }}</h2>
            </div>
        </div>

        <div class="summary-line">
            <span>Booking #{{ $booking->id }}</span>
            <span>{{ $booking->booking_date->format('d M Y') }}</span>
        </div>

        <div class="summary-line">
            <span>Scheduled time</span>
            <strong>{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</strong>
        </div>

        <div class="summary-line total">
            <span>Total amount</span>
            <strong>₹{{ number_format($booking->amount, 2) }}</strong>
        </div>

        <a class="back-link" href="{{ route('bookings.show', $booking) }}">← Back to booking</a>
    </div>

    <div class="payment-form card reveal">
        <div class="payment-form-head">
            <div>
                <small class="eyebrow">PAYMENT METHOD</small>
                <h2>Pay securely</h2>
            </div>
            <span class="secure-pill">🔒 Secure</span>
        </div>

        <form method="POST" action="{{ route('payments.pay', $booking) }}" id="paymentForm">
            @csrf

            <div class="method-tabs">
                <label class="method-option active">
                    <input type="radio" name="method" value="card" checked>
                    <span>💳</span>
                    <div>
                        <strong>Card</strong>
                        <small>Credit or debit card</small>
                    </div>
                </label>

                <label class="method-option">
                    <input type="radio" name="method" value="cash">
                    <span>💵</span>
                    <div>
                        <strong>Cash</strong>
                        <small>Pay at service</small>
                    </div>
                </label>
            </div>

            <div id="cardFields">
                <label class="payment-label">
                    Cardholder name
                    <input type="text" name="card_name" value="{{ old('card_name') }}"
                           placeholder="John Doe" autocomplete="cc-name">
                </label>

                <label class="payment-label">
                    Card number
                    <div class="card-number-wrap">
                        <input type="text" id="cardNumber" name="card_number"
                               value="{{ old('card_number') }}"
                               placeholder="1234 5678 9012 3456"
                               inputmode="numeric" maxlength="19" autocomplete="cc-number">
                        <span>💳</span>
                    </div>
                    <span class="field-error"></span>
                </label>

                <div class="payment-row">
                    <label class="payment-label">
                        Expiry
                        <input type="text" id="expiry" name="expiry" value="{{ old('expiry') }}"
                               placeholder="MM/YY" maxlength="5" inputmode="numeric" autocomplete="cc-exp">
                        <span class="field-error"></span>
                    </label>

                    <label class="payment-label">
                        CVV
                        <input type="password" id="cvv" name="cvv" value="{{ old('cvv') }}"
                               placeholder="123" maxlength="3" inputmode="numeric" autocomplete="cc-csc">
                        <span class="field-error"></span>
                    </label>
                </div>

                <div class="pay-note">
                    <span>🔒</span>
                    <span>Your card details are encrypted in transit and never stored on our servers.</span>
                </div>
            </div>

            <div id="cashNote" class="pay-note" style="display:none">
                <span>💵</span>
                <span>No online payment will be processed. The booking will be confirmed for cash payment.</span>
            </div>

            <button type="submit" class="primary full payment-submit" id="payButton"
                    data-total-label="Pay ₹{{ number_format($booking->amount, 2) }} & Confirm Booking">
                Pay ₹{{ number_format($booking->amount, 2) }} & Confirm Booking
            </button>

            <p class="payment-security">🔒 Your payment information is encrypted and processed securely.</p>
        </form>
    </div>
</div>
@endsection
