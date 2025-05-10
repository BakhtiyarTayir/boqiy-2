@php
    $isUpdate = isset($freeProduct) && !empty($freeProduct);
@endphp
<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{{ get_phrase('Create New Free Product') }}</h4>
                    <a href="{{ route('admin.marketplace.free.products') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> {{ get_phrase('Back to Products') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap ">
                <form method="POST" action="{{ $isUpdate && isset($freeProduct) ? route('admin.marketplace.free.products.update', ['id' => $freeProduct->id]) : route('admin.marketplace.free.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <label class="col-md-2 text-end">
                            Product
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <select name="product_type_id" class="form-select eForm-control select2" @if ($isUpdate && isset($freeProduct)) disabled @endif required>
                                @php
                                    $product_type_id = old('product_type_id') ?: ($isUpdate && isset($freeProduct) ? $freeProduct->product_type_id : null);
                                @endphp
                                @if (!empty($product_types) && count($product_types))
                                    @foreach ($product_types as $product_type)
                                        @php
                                            $selected = $product_type_id == $product_type->id ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $product_type->id }}" {{ $selected }}>
                                            {{ $product_type->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
    
                            @if ($isUpdate && isset($freeProduct))
                                <input type="hidden" name="product_type_id" value="{{ $freeProduct->product_type_id }}">
                            @endif
    
                            @error('product_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <label for="text_for_sponsor" class="form-label col-md-2 text-end">
                            Sponsor
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <select name="sponsor_id"
                                    class="form-select eForm-control select2"
                                    @if ($isUpdate && isset($freeProduct) && $freeProduct->is_payment_sponsor)
                                    disabled
                                    @endif
                                    required>
                                @php
                                    $sponsor_id = old('sponsor_id') ?: ($isUpdate && isset($freeProduct) ? $freeProduct->sponsor_id : null);
                                @endphp
                                @if (!empty($users) && count($users))
                                    @foreach ($users as $user)
                                        @php
                                            $selected = $sponsor_id == $user->id ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $user->id }}" {{ $selected }}> {{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($isUpdate && isset($freeProduct) && $freeProduct->is_payment_sponsor)
                                <input type="hidden" name="sponsor_id" value="{{ $freeProduct->sponsor_id }}">
                            @endif
                            @error('sponsor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
    
                    <div class="row mt-4">
                        <label for="text_for_sponsor" class="form-label col-md-2 text-end">
                            Qabul qiluvchi
                        </label>
                        <div class="col-md-6">
                            <select name="receiver_id"
                                    class="form-select eForm-control select2"
                                    @if ($isUpdate && isset($freeProduct) && $freeProduct->is_sold)
                                        disabled
                                    @endif
                            >
                                <option value="">Tanlanmadi</option>
                                @php
                                    $receiver_id = old('receiver_id') ?: ($isUpdate && isset($freeProduct) ? $freeProduct->receiver_id : null);
                                @endphp

                                @if (!empty($users) && count($users))
                                    @foreach ($users as $user)
                                        @php
                                            $selected = $receiver_id == $user->id ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $user->id }}" {{ $selected }}> {{ $user->name . ' email: ' . $user->email   }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($isUpdate && isset($freeProduct) && $freeProduct->is_sold)
                                <input type="hidden" name="receiver_id" value="{{ $freeProduct->receiver_id }}">
                            @endif
                            @error('receiver_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
    
                    <div class="row mt-4">
                        <label for="deadline_hour" class="form-label col-md-2 text-end">
                            Topshirilgandan keyingi ko'rinish vaqti
                        </label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" min="1" step="1"  id="deadline_hour" name="deadline_hour"
                                   value="{{ old('deadline_hour') ?? ($isUpdate && isset($freeProduct->deadline_hour) ? $freeProduct->deadline_hour : 36) }}">
                            @error('receiver_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4 form-check my-4">
                        <div class="col-md-6 offset-2">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active"
                                   value="1"
                                    {{ old('is_active', $isUpdate && isset($freeProduct) ? $freeProduct->is_active : 0) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Bepul tovarlar ro'yxatida turish
                            </label>
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