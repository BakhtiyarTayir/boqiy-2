<div class="main_content">
    
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{{ get_phrase('Edit Product') }}</h4>
                    <a href="{{ route('admin.marketplace.products') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> {{ get_phrase('Back to Products') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap">
                <form method="POST" action="{{ route('admin.marketplace.products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="eForm-layouts">
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ get_phrase('Title') }} <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $product->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ get_phrase('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="user_id" class="form-label">{{ get_phrase('Seller') }} <span class="required">*</span></label>
                                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                                <option value="">{{ get_phrase('Select Seller') }}</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('user_id', $product->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">{{ get_phrase('Location') }}</label>
                                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $product->location) }}">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">{{ get_phrase('Price') }} <span class="required">*</span></label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">{{ get_phrase('Currency') }}</label>
                                            <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency">
                                                <option value="">{{ get_phrase('Select Currency') }}</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}" {{ old('currency', $product->currency_id) == $currency->id ? 'selected' : '' }}>{{ $currency->name }} ({{ $currency->symbol }})</option>
                                                @endforeach
                                            </select>
                                            @error('currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">{{ get_phrase('Category') }}</label>
                                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                                <option value="">{{ get_phrase('Select Category') }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category', $product->category) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="brand" class="form-label">{{ get_phrase('Brand') }}</label>
                                            <select class="form-select @error('brand') is-invalid @enderror" id="brand" name="brand">
                                                <option value="">{{ get_phrase('Select Brand') }}</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand', $product->brand) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="condition" class="form-label">{{ get_phrase('Condition') }} <span class="required">*</span></label>
                                            <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition" required>
                                                <option value="">{{ get_phrase('Select Condition') }}</option>
                                                <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>{{ get_phrase('New') }}</option>
                                                <option value="used" {{ old('condition', $product->condition) == 'used' ? 'selected' : '' }}>{{ get_phrase('Used') }}</option>
                                            </select>
                                            @error('condition')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">{{ get_phrase('Status') }} <span class="required">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="">{{ get_phrase('Select Status') }}</option>
                                                <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>{{ get_phrase('Active') }}</option>
                                                <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>{{ get_phrase('Out of Stock') }}</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="buy_link" class="form-label">{{ get_phrase('Buy Link') }}</label>
                                    <input type="url" class="form-control @error('buy_link') is-invalid @enderror" id="buy_link" name="buy_link" value="{{ old('buy_link', $product->buy_link) }}">
                                    <small class="text-muted">{{ get_phrase('Optional. Add an external link where customers can directly purchase this product (e.g. a link to an online store).') }}</small>
                                    @error('buy_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="eForm-layouts">
                                <div class="mb-3">
                                    <label for="multiple_files" class="form-label">{{ get_phrase('Product Images') }}</label>
                                    <input type="file" class="form-control @error('multiple_files') is-invalid @enderror" id="multiple_files" name="multiple_files[]" multiple accept="image/*">
                                    <small class="text-muted">{{ get_phrase('You can select multiple images. The first image will be used as the main image.') }}</small>
                                    @error('multiple_files')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="replace_images" name="replace_images" value="1">
                                        <label class="form-check-label" for="replace_images">
                                            {{ get_phrase('Replace existing images') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="image_preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                                
                                @if(count($product_images) > 0)
                                <div class="mb-3">
                                    <label class="form-label">{{ get_phrase('Current Images') }}</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($product_images as $img)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/marketplace/thumbnail/' . $img->file_name) }}" alt="{{ $product->title }}" class="img-thumbnail" style="height: 100px; width: 100px; object-fit: cover;">
                                            <a href="{{ route('admin.marketplace.products.remove-image', $img->id) }}" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="return confirm('{{ get_phrase('Are you sure you want to remove this image?') }}')">
                                                <i class="bi bi-x"></i>
                                            </a>
                                            @if($product->image == $img->file_name)
                                            <span class="badge bg-primary position-absolute bottom-0 start-0">{{ get_phrase('Main') }}</span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Update Product') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Image preview
        $('#multiple_files').on('change', function() {
            $('#image_preview').html('');
            if (this.files) {
                $.each(this.files, function(i, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image_preview').append('<div class="position-relative"><img src="' + e.target.result + '" alt="Preview" class="img-thumbnail" style="height: 100px; width: 100px; object-fit: cover;"></div>');
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
@endsection 