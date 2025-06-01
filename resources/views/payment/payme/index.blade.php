@extends('payment.index')
@section('payment_gateway_content')
    <div class="payme-payment-area">
        <div class="payment-gateway-header">
            <img src="{{ asset('assets/images/payment-gateway/payme.png') }}" alt="Payme">
        </div>
        
        <div class="payment-gateway-body">
            <form action="{{ route('payment.create', ['identifier' => 'payme']) }}" method="get">
                @csrf
                <div class="payment-info">
                    <div class="payment-amount">
                        <h5>{{ get_phrase('Payable amount') }}</h5>
                        <p>{{ currency($payment_details['payable_amount']) }}</p>
                    </div>
                </div>
                
                <div class="payment-method">
                    <div class="payment-instruction">
                        <p>{{ get_phrase('Click the button below to pay with Payme. You will be redirected to Payme to complete your purchase securely.') }}</p>
                    </div>
                    
                    <button type="submit" class="payment-button">
                        {{ get_phrase('Pay with Payme') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 