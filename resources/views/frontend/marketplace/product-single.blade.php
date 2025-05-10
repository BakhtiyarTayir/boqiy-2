@foreach ($products as $key => $product )
    <div class="col-6 col-sm-6 col-lg-6 mb-3 @if(str_contains(url()->current(), '/products')) single-item-countable @endif">
        <div class="card product m_product">
            <a href="{{ route('single.product',$product->id) }}" class="thumbnail-196-196 mb-3">
                <img src="{{ route('showProductTypeFile', ['product_type_id' => $product->product_type_id]) }}" alt="{{ $product->name }}" style="max-width: 300px;">
            </a>
            <div class="p-8 mt-4">
                <h3 class="h6 mt-3">
                    <a href="{{ route('single.product', $product->id) }}">
                        {{ ellipsis($product->name, 18) }}
                    </a>
                    @if ($product->is_sold)
                        <span class="badge bg-warning text-dark ml-2">Sovg‘a topshirilgan</span>
                    @endif
                </h3>
                
                <a href="{{ route('single.product',$product->id) }}" class="btn common_btn d-block mt-2">
                    {{ $product->price_for_every_one }} so`m
                </a>
                
                @if (!$product->is_anonymous_sponsor)
                    <hr class="mt-3">
                    <h5>Homiy</h5>
                    <div class="pb-author align-items-center mt-3">
                        <a href="{{ route('user.profile.view', $product->sponsor_id) }}">
                            <img class="user_image_proifle_height"
                                 src="{{ route('showUserPhoto', $product->sponsor_id) }}"
                                 alt="{{ $product->sponsor_name }}"
                                 style="width: 20%; height: auto"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';">
                        
                        </a>
                        <div class="pb-info mt-1">
                            <a href="{{ route('user.profile.view', $product->sponsor_id) }}" class="h6 text-primary">
                                {{ $product->sponsor_name }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @if (isset($search)&&!empty($search))
        @if ($key == 2)
            @break
        @endif
    @endif
@endforeach