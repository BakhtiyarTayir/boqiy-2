<div class="purchase-history-wrap p-3 radius-8 bg-white">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="sub-title">{{ get_phrase('Purchase History') }}</h3>
            
            @if (session('success_message'))
                <div class="alert alert-success">
                    {{ session('success_message') }}
                </div>
            @endif
            
            @if (count($orders) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Order ID') }}</th>
                                <th scope="col">{{ get_phrase('Product') }}</th>
                                <th scope="col">{{ get_phrase('Seller') }}</th>
                                <th scope="col">{{ get_phrase('Amount') }}</th>
                                <th scope="col">{{ get_phrase('Status') }}</th>
                                <th scope="col">{{ get_phrase('Date') }}</th>
                                <th scope="col">{{ get_phrase('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <th scope="row">#{{ $order->id }}</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($order->product && $order->product->image)
                                                <img src="{{ get_product_image($order->product->image, 'thumbnail') }}" alt="{{ $order->product->title }}" class="rounded" width="40">
                                            @else
                                                <div class="placeholder-img rounded bg-light" style="width: 40px; height: 40px"></div>
                                            @endif
                                            <div class="ms-2">
                                                <div class="fw-bold">{{ $order->product ? $order->product->title : get_phrase('Product Removed') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->seller)
                                            <a href="{{ route('user.profile.view', $order->seller->id) }}">{{ $order->seller->name }}</a>
                                        @else
                                            {{ get_phrase('Unknown seller') }}
                                        @endif
                                    </td>
                                    <td>{{ $order->product && $order->product->getCurrency ? $order->product->getCurrency->symbol : '' }} {{ number_format($order->amount, 2) }}</td>
                                    <td>
                                        @if($order->status == 'completed')
                                            <span class="badge bg-success">{{ get_phrase('Completed') }}</span>
                                        @elseif($order->status == 'pending')
                                            <span class="badge bg-warning">{{ get_phrase('Pending') }}</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">{{ get_phrase('Cancelled') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        @if($order->product)
                                            <a href="{{ route('single.product', $order->product->id) }}" class="btn btn-sm btn-outline-primary">{{ get_phrase('View Product') }}</a>
                                        @endif
                                        
                                        @if($order->seller)
                                            <a href="{{ route('chat', ['reciver' => $order->seller->id]) }}" class="btn btn-sm btn-outline-secondary mt-1">{{ get_phrase('Contact Seller') }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-wrapper mt-3">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    {{ get_phrase('You have not made any purchases yet.') }}
                </div>
                
                <a href="{{ route('allproducts') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag mr-2"></i> {{ get_phrase('Browse Products') }}
                </a>
            @endif
        </div>
    </div>
</div> 