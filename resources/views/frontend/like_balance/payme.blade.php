<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ get_phrase('Payment via Payme') }}</h5>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2>{{ number_format($amount, 2) }} {{ get_phrase('sum') }}</h2>
                        <p class="text-muted">{{ get_phrase('Amount to pay') }}</p>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-0">{{ get_phrase('For the demonstration of the system, we will use a form to simulate payment through Payme. In a real project, there would be an integration with the Payme API.') }}</p>
                    </div>
                    
                    <form action="{{ route('like_balance.payme_callback') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $amount }}">
                        <input type="hidden" name="payment_id" value="{{ uniqid('payme_') }}">
                        
                        <div class="mb-3">
                            <label for="card_number" class="form-label">{{ get_phrase('Card Number') }}</label>
                            <input type="text" class="form-control" id="card_number" placeholder="8600 **** **** ****" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expiry" class="form-label">{{ get_phrase('Expiry Date') }}</label>
                                <input type="text" class="form-control" id="expiry" placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cvv" class="form-label">{{ get_phrase('CVV') }}</label>
                                <input type="text" class="form-control" id="cvv" placeholder="***" required>
                            </div>
                        </div>
                        
                        <div class="form-group text-end">
                            <a href="{{ route('like_balance.topup') }}" class="btn btn-secondary">{{ get_phrase('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Pay') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mask for card number
        const cardInput = document.getElementById('card_number');
        cardInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) value = value.slice(0, 16);
            
            // Format with spaces after every 4 digits
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += ' ';
                formattedValue += value[i];
            }
            
            e.target.value = formattedValue;
        });
        
        // Mask for expiry date
        const expiryInput = document.getElementById('expiry');
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 4) value = value.slice(0, 4);
            
            // Format as MM/YY
            if (value.length > 2) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            
            e.target.value = value;
        });
        
        // Mask for CVV
        const cvvInput = document.getElementById('cvv');
        cvvInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3) value = value.slice(0, 3);
            e.target.value = value;
        });
    });
</script>
@endpush