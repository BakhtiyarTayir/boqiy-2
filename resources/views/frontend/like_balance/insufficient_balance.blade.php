<div class="insufficient-balance-modal">
    <div class="text-center mb-4">
        <img src="{{ asset('storage/images/gift-icon.svg') }}" alt="Gift Icon" class="mb-3" style="width: 80px;">
        <h4 class="modal-title">{{ get_phrase('Insufficient Balance') }}</h4>
        <p class="text-muted">{{ get_phrase('You do not have enough balance to send this gift. Top up your balance to continue.') }}</p>
    </div>
    
    <div class="d-flex justify-content-center">
        <a href="{{ route('like_balance.topup') }}" class="btn btn-primary">
            {{ get_phrase('Top Up Balance') }}
        </a>
    </div>
</div> 