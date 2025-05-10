<!-- Order Details -->
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Order Details') }} #{{ $order->id }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="{{ route('admin.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                        <li><a href="#">{{ get_phrase('Marketplace') }}</a></li>
                        <li><a href="{{ route('admin.marketplace.orders') }}">{{ get_phrase('Orders') }}</a></li>
                        <li><a href="#">{{ get_phrase('Order Details') }}</a></li>
                    </ul>
                </div>
                <div class="export-btn-area">
                    <a href="{{ route('admin.marketplace.orders') }}" class="export_btn">{{ get_phrase('Back to Orders') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <!-- Order Status -->
            <div class="order-status-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-3">{{ get_phrase('Order Status') }}:</h5>
                        <div class="d-flex align-items-center">
                            @if($order->status == 'completed')
                                <span class="badge bg-success p-2 fs-5">{{ get_phrase('Completed') }}</span>
                            @elseif($order->status == 'pending')
                                <span class="badge bg-warning p-2 fs-5">{{ get_phrase('Pending') }}</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger p-2 fs-5">{{ get_phrase('Cancelled') }}</span>
                            @else
                                <span class="badge bg-secondary p-2 fs-5">{{ $order->status }}</span>
                            @endif
                            
                            <button class="btn btn-primary ms-3" onclick="showAjaxModal('{{ route('load_modal_content', ['view_path' => 'backend.admin.marketplace.update_status_modal', 'order_id' => $order->id]) }}', '{{ get_phrase('Update Order Status') }}')">
                                {{ get_phrase('Update Status') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <h5 class="mb-3">{{ get_phrase('Order Date') }}:</h5>
                        <p class="fs-5">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Order Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ get_phrase('Order Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ get_phrase('Order ID') }}:</strong> #{{ $order->id }}
                            </div>
                            <div class="mb-3">
                                <strong>{{ get_phrase('Order Amount') }}:</strong> 
                                @if($order->product && $order->product->getCurrency)
                                    {{ $order->product->getCurrency->symbol }} {{ number_format($order->amount, 2) }}
                                @else
                                    {{ number_format($order->amount, 2) }}
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>{{ get_phrase('Status') }}:</strong> 
                                @if($order->status == 'completed')
                                    <span class="badge bg-success">{{ get_phrase('Completed') }}</span>
                                @elseif($order->status == 'pending')
                                    <span class="badge bg-warning">{{ get_phrase('Pending') }}</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">{{ get_phrase('Cancelled') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endif
                            </div>
                            @if($order->completed_at)
                            <div class="mb-3">
                                <strong>{{ get_phrase('Completed Date') }}:</strong> {{ $order->completed_at->format('d M Y, h:i A') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Product Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ get_phrase('Product Information') }}</h5>
                        </div>
                        <div class="card-body">
                            @if($order->product)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <img src="{{ get_product_image($order->product->image, 'thumbnail') }}" alt="{{ $order->product->title }}" class="rounded" width="80">
                                    </div>
                                    <div>
                                        <h5><a href="{{ route('single.product', $order->product->id) }}" target="_blank">{{ $order->product->title }}</a></h5>
                                        <p class="mb-1">{{ get_phrase('Price') }}: {{ $order->product->getCurrency ? $order->product->getCurrency->symbol : '' }} {{ number_format($order->product->price, 2) }}</p>
                                        <p class="mb-0">{{ get_phrase('Condition') }}: {{ ucfirst($order->product->condition) }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    {{ get_phrase('Product has been removed') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Buyer Information -->
                <div class="col-md-6 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ get_phrase('Buyer Information') }}</h5>
                        </div>
                        <div class="card-body">
                            @if($order->buyer)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <img src="{{ get_user_image($order->buyer->photo, 'optimized') }}" alt="{{ $order->buyer->name }}" class="rounded-circle" width="50" height="50">
                                    </div>
                                    <div>
                                        <h5><a href="{{ route('user.profile.view', $order->buyer->id) }}" target="_blank">{{ $order->buyer->name }}</a></h5>
                                        <p class="mb-1">{{ get_phrase('Email') }}: {{ $order->buyer->email }}</p>
                                        @if($order->buyer->phone)
                                            <p class="mb-0">{{ get_phrase('Phone') }}: {{ $order->buyer->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    {{ get_phrase('Buyer information is not available') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Seller Information -->
                <div class="col-md-6 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ get_phrase('Seller Information') }}</h5>
                        </div>
                        <div class="card-body">
                            @if($order->seller)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <img src="{{ get_user_image($order->seller->photo, 'optimized') }}" alt="{{ $order->seller->name }}" class="rounded-circle" width="50" height="50">
                                    </div>
                                    <div>
                                        <h5><a href="{{ route('user.profile.view', $order->seller->id) }}" target="_blank">{{ $order->seller->name }}</a></h5>
                                        <p class="mb-1">{{ get_phrase('Email') }}: {{ $order->seller->email }}</p>
                                        @if($order->seller->phone)
                                            <p class="mb-0">{{ get_phrase('Phone') }}: {{ $order->seller->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    {{ get_phrase('Seller information is not available') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Information -->
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ get_phrase('Shipping Information') }}</h5>
                        </div>
                        <div class="card-body">
                            @if($order->shipping_address)
                                <p>{{ $order->shipping_address }}</p>
                            @else
                                <div class="alert alert-info">
                                    {{ get_phrase('No shipping address provided') }}
                                </div>
                            @endif
                            
                            @if($order->notes)
                                <h6 class="mt-4">{{ get_phrase('Notes') }}:</h6>
                                <p>{{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 