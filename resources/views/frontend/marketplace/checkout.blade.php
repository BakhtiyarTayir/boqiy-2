<div class="checkout-wrap p-3 radius-8 bg-white">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="sub-title">{{ get_phrase('Checkout') }}</h3>
            
            @if (session('error_message'))
                <div class="alert alert-danger">
                    {{ session('error_message') }}
                </div>
            @endif
            
            <div class="product-info mb-4">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <img class="rounded w-100" src="{{ get_product_image($product_image[0]->file_name,"thumbnail") }}" alt="{{ $product->title }}">
                    </div>
                    <div class="col-md-10">
                        <h4 class="product-title mb-1">{{ $product->title }}</h4>
                        <p class="location mb-1"><strong>{{ get_phrase('Location') }}:</strong> {{ $product->location }}</p>
                        <p class="seller mb-1"><strong>{{ get_phrase('Seller') }}:</strong> {{ $product->getUser->name }}</p>
                        <h5 class="price text-primary">{{ $product->getCurrency->symbol }} {{ number_format($product->price, 2) }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="wallet-info mb-4 p-3 bg-light rounded">
                <h4 class="mb-3">{{ get_phrase('Wallet Information') }}</h4>
                <p><strong>{{ get_phrase('Current Balance') }}:</strong> {{ $product->getCurrency->symbol }} {{ number_format($wallet ? $wallet->balance : 0, 2) }}</p>
                
                @if (!$wallet || $wallet->balance < $product->price)
                    <div class="alert alert-danger">
                        {{ get_phrase('Insufficient balance. Please add funds to your wallet.') }}
                    </div>
                    <a href="{{ route('wallet.index') }}" class="btn btn-primary">{{ get_phrase('Add Funds') }}</a>
                @else
                    <div class="alert alert-success">
                        {{ get_phrase('You have sufficient balance to purchase this product.') }}
                    </div>
                @endif
            </div>
            
            @if ($wallet && $wallet->balance >= $product->price)
                <form action="{{ route('marketplace.purchase', $product->id) }}" method="post">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="shipping_address" class="form-label">{{ get_phrase('Shipping Address') }}:</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">{{ get_phrase('Notes') }} ({{ get_phrase('optional') }}):</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="purchase-summary mb-4">
                        <h4 class="mb-3">{{ get_phrase('Purchase Summary') }}</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ get_phrase('Product Price') }}:</span>
                            <span>{{ $product->getCurrency->symbol }} {{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ get_phrase('Shipping') }}:</span>
                            <span>{{ get_phrase('Free') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>{{ get_phrase('Total') }}:</strong>
                            <strong>{{ $product->getCurrency->symbol }} {{ number_format($product->price, 2) }}</strong>
                        </div>
                    </div>
                    
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms_agree" required>
                        <label class="form-check-label" for="terms_agree">
                            {{ get_phrase('I agree to the terms and conditions') }}
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg common_btn w-100">
                        <i class="fas fa-shopping-cart mr-2"></i> {{ get_phrase('Confirm Purchase') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div> 