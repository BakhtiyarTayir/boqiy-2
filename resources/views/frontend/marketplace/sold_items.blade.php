<div class="sold-items-wrap p-3 radius-8 bg-white">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="sub-title">{{ get_phrase('Sold Items') }}</h3>
            
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
                                <th scope="col">{{ get_phrase('Buyer') }}</th>
                                <th scope="col">{{ get_phrase('Amount') }}</th>
                                <th scope="col">{{ get_phrase('Status') }}</th>
                                <th scope="col">{{ get_phrase('Date') }}</th>
                                <th scope="col">{{ get_phrase('Shipping Details') }}</th>
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
                                        @if($order->buyer)
                                            <a href="{{ route('user.profile.view', $order->buyer->id) }}">{{ $order->buyer->name }}</a>
                                        @else
                                            {{ get_phrase('Unknown buyer') }}
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
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#shippingDetails{{ $order->id }}">
                                            {{ get_phrase('View Details') }}
                                        </button>
                                        
                                        <!-- Shipping Details Modal -->
                                        <div class="modal fade" id="shippingDetails{{ $order->id }}" tabindex="-1" aria-labelledby="shippingDetailsLabel{{ $order->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="shippingDetailsLabel{{ $order->id }}">{{ get_phrase('Shipping Details') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h6>{{ get_phrase('Shipping Address') }}:</h6>
                                                        <p>{{ $order->shipping_address ?? get_phrase('No shipping address provided') }}</p>
                                                        
                                                        @if($order->notes)
                                                        <h6 class="mt-3">{{ get_phrase('Notes') }}:</h6>
                                                        <p>{{ $order->notes }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->product)
                                            <a href="{{ route('single.product', $order->product->id) }}" class="btn btn-sm btn-outline-primary">{{ get_phrase('View Product') }}</a>
                                        @endif
                                        
                                        @if($order->buyer)
                                            <a href="{{ route('chat', ['reciver' => $order->buyer->id]) }}" class="btn btn-sm btn-outline-secondary mt-1">{{ get_phrase('Contact Buyer') }}</a>
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
                    {{ get_phrase('You have not sold any items yet.') }}
                </div>
                
                <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> {{ get_phrase('Create Product') }}
                </a>
            @endif
        </div>
    </div>
</div> 