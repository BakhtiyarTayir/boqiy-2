@if (0)
<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{{ get_phrase('Create New Product') }}</h4>
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
                <form method="POST" action="{{ route('admin.marketplace.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="eForm-layouts">
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ get_phrase('Title') }} <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ get_phrase('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
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
                                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}">
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
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
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
                                                    <option value="{{ $currency->id }}" {{ old('currency') == $currency->id ? 'selected' : '' }}>{{ $currency->name }} ({{ $currency->symbol }})</option>
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
                                                    <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                                    <option value="{{ $brand->id }}" {{ old('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
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
                                                <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>{{ get_phrase('New') }}</option>
                                                <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>{{ get_phrase('Used') }}</option>
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
                                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>{{ get_phrase('Active') }}</option>
                                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>{{ get_phrase('Out of Stock') }}</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="buy_link" class="form-label">{{ get_phrase('Buy Link') }}</label>
                                    <input type="url" class="form-control @error('buy_link') is-invalid @enderror" id="buy_link" name="buy_link" value="{{ old('buy_link') }}">
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
                                <div id="image_preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Create Product') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@php
    $isUpdate = !empty($productType);
@endphp
<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{{ get_phrase('Create New Product') }}</h4>
                    <a href="{{ route('admin.marketplace.products') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> {{ get_phrase('Back to Products') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap ">
                <form method="POST" action="{{ $isUpdate ? route('admin.marketplace.products.update', ['id' => $productType->id]) : route('admin.marketplace.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <label class="col-md-2 text-end">
                            Nomi
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') ? old('name') : ($isUpdate ? $productType->name : '') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
    
                    <div class="row mt-4">
                        <label for="text_for_sponsor" class="form-label col-md-2 text-end">
                            Text (sponsor)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                             <textarea class="form-control"
                                       id="text_for_sponsor"
                                       name="text_for_sponsor"
                                       rows="5"
                                       required
                                       tabindex="2">{{ old('text_for_sponsor') ? old('text_for_sponsor') : ($isUpdate ? $productType->text_for_sponsor : '') }}</textarea>
    
                            @error('text_for_sponsor')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
    
                    <div class="row mt-4">
                        <label for="text_for_sponsor" class="form-label col-md-2 text-end">
                            Text (hammaga)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                             <textarea class="form-control"
                                       id="text_for_every_one"
                                       name="text_for_every_one"
                                       rows="5"
                                       tabindex="2"
                                       required
                             >{{ old('text_for_every_one') ? old('text_for_every_one') : ($isUpdate ? $productType->text_for_every_one : '') }}</textarea>
                            @error('text_for_every_one')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-4">
                        <label class="col-md-2 text-end">
                            Narx (sponsor)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <input class="form-control" type="number"
                                   id="price_for_sponsor"
                                   name="price_for_sponsor"
                                   min="0" step="0.01" required
                                   value="{{ old('price_for_sponsor') ? old('price_for_sponsor') : ($isUpdate ? $productType->price_for_sponsor : '') }}"
                            >
                        </div>
                    </div>
                    <div class="row mt-4">
                        <label class="col-md-2 text-end">
                            Narx (hammaga)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <input type="number"
                                   class="form-control"
                                   id="price_for_every_one"
                                   name="price_for_every_one"
                                   min="0" step="0.01" required
                                   value="{{ old('price_for_every_one') ? old('price_for_every_one') : ($isUpdate ? $productType->price_for_every_one : '') }}"
                            >
                        </div>
                    </div>
                    <div class="row mt-4">
                        <label for="multiple_files" class="form-label col-md-2 text-end">Image</label>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="file"
                                       class="form-control"
                                       id="image"
                                       name="image"
                                       accept="image/png, image/jpg, image/jpeg">
                                @if (0)
                                    <small class="text-muted">{{ get_phrase('You can select multiple images. The first image will be used as the main image.') }}</small>
                                @endif
                                @error('multiple_files')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="image_preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Save') }}</button>
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