@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <div class="product-image">
                <img src="{{ asset('storage/marketplace/thumbnail/' . $product->image) }}" alt="{{ $product->title }}" class="img-fluid">
                @if($product->sponsor && $product->sponsor->sponsor_image)
                    <div class="sponsor-logo" style="position: absolute; bottom: 10px; right: 10px; width: 100px; height: 100px; background: white; padding: 5px; border-radius: 5px;">
                        <img src="{{ asset('storage/sponsor_images/' . $product->sponsor->sponsor_image) }}" alt="Sponsor Logo" class="img-fluid">
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <h1>{{ $product->title }}</h1>
            <p class="text-muted">{{ $product->description }}</p>
            
            <div class="price-section mb-3">
                <h3>
                    @if($product->getCurrency)
                        {{ $product->getCurrency->symbol }} {{ number_format($product->price, 2) }}
                    @else
                        {{ number_format($product->price, 2) }}
                    @endif
                </h3>
            </div>

            @if($product->category == 'sponsor')
                <div class="sponsor-section mb-4">
                    <div class="alert alert-info">
                        <h5>Sponsor this Product</h5>
                        <p>By sponsoring this product, you will receive 1000 views and your company information will be displayed here.</p>
                    </div>
                    <a href="{{ route('product.sponsor.form', $product->id) }}" class="btn btn-primary">Sponsor</a>
                </div>
            @else
                <div class="buy-section mb-4">
                    <a href="{{ $product->buy_link ?? '#' }}" class="btn btn-primary">Buy Now</a>
                </div>
            @endif

            @if($product->sponsor)
                <div class="sponsor-info mt-4">
                    <h4>Sponsored by: {{ $product->sponsor->company_name }}</h4>
                    <p>Phone: {{ $product->sponsor->phone_number }}</p>
                    <div class="social-links">
                        @if($product->sponsor->instagram)
                            <a href="{{ $product->sponsor->instagram }}" target="_blank" class="me-2"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($product->sponsor->telegram)
                            <a href="{{ $product->sponsor->telegram }}" target="_blank" class="me-2"><i class="fab fa-telegram"></i></a>
                        @endif
                        @if($product->sponsor->youtube)
                            <a href="{{ $product->sponsor->youtube }}" target="_blank" class="me-2"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if($product->sponsor->twitter)
                            <a href="{{ $product->sponsor->twitter }}" target="_blank" class="me-2"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($product->sponsor->facebook)
                            <a href="{{ $product->sponsor->facebook }}" target="_blank" class="me-2"><i class="fab fa-facebook"></i></a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 