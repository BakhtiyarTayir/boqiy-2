<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ get_phrase('Sponsor Product') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.sponsor.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label">{{ get_phrase('Company Name') }}</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">{{ get_phrase('Phone Number') }}</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="instagram" class="form-label">{{ get_phrase('Instagram URL') }}</label>
                            <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                id="instagram" name="instagram" value="{{ old('instagram') }}">
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telegram" class="form-label">{{ get_phrase('Telegram URL') }}</label>
                            <input type="text" class="form-control @error('telegram') is-invalid @enderror" 
                                id="telegram" name="telegram" value="{{ old('telegram') }}">
                            @error('telegram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="youtube" class="form-label">{{ get_phrase('YouTube URL') }}</label>
                            <input type="text" class="form-control @error('youtube') is-invalid @enderror" 
                                id="youtube" name="youtube" value="{{ old('youtube') }}">
                            @error('youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="twitter" class="form-label">{{ get_phrase('Twitter URL') }}</label>
                            <input type="text" class="form-control @error('twitter') is-invalid @enderror" 
                                id="twitter" name="twitter" value="{{ old('twitter') }}">
                            @error('twitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="facebook" class="form-label">{{ get_phrase('Facebook URL') }}</label>
                            <input type="text" class="form-control @error('facebook') is-invalid @enderror" 
                                id="facebook" name="facebook" value="{{ old('facebook') }}">
                            @error('facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sponsor_image" class="form-label">{{ get_phrase('Sponsor Image/Logo') }}</label>
                            <input type="file" class="form-control @error('sponsor_image') is-invalid @enderror" 
                                id="sponsor_image" name="sponsor_image">
                            @error('sponsor_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="views_purchased" class="form-label">{{ get_phrase('Additional Views (Optional)') }}</label>
                            <input type="number" class="form-control @error('views_purchased') is-invalid @enderror" 
                                id="views_purchased" name="views_purchased" value="{{ old('views_purchased', 1000) }}" min="1000">
                            <small class="text-muted">{{ get_phrase('Minimum 1000 views included') }}</small>
                            @error('views_purchased')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info">
                                <h5>{{ get_phrase('Sponsorship Details:') }}</h5>
                                <p>{{ get_phrase('By sponsoring this product, you will receive:') }}</p>
                                <ul>
                                    <li>{{ get_phrase('1000 base views') }}</li>
                                    <li>{{ get_phrase('Your logo/image displayed on the product') }}</li>
                                    <li>{{ get_phrase('Social media links displayed with the product') }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Proceed to Payment') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>