@foreach ( $products as $key=> $product )
    <div class="col-6 col-sm-6 col-lg-6 mb-3 @if(str_contains(url()->current(), '/products')) single-item-countable @endif">
        <div class="card product m_product">
            <a href="{{ route('sponsorProductView',$product->id) }}" class="thumbnail-196-196 mb-3">
                <img src="{{ route('showProductTypeFile', ['product_type_id' => $product->id]) }}"
                     alt="{{ $product->name }}"
                     class="img-fluid rounded"
                     style="max-width: 100%; height: auto;">

            </a>
            
            <div class="p-8 mt-5">
                <h3 class="h6 my-3"><a href="{{ route('single.product', $product->id) }}">{{ ellipsis($product->name, 18) }}</a></h3>
                <a href="{{ route('sponsorProductView',$product->id) }}" class="btn common_btn d-block">{{ $product->price_for_sponsor }} so`m</a>
            </div>
        </div>
    </div>
    
    @if (isset($search)&&!empty($search))
        @if ($key == 2)
            @break
        @endif
    @endif
@endforeach