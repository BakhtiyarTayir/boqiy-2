<div class="main_content">
    <!-- Marketplace Orders -->
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Marketplace Orders') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="{{ route('admin.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                        <li><a href="#">{{ get_phrase('Marketplace') }}</a></li>
                        <li><a href="#">{{ get_phrase('Orders') }}</a></li>
                    </ul>
                </div>
                <div class="export-btn-area d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.marketplace.orders') }}" class="export_btn">{{ get_phrase('All Orders') }}</a>
                    <a href="{{ route('admin.marketplace.orders.pending') }}" class="export_btn">{{ get_phrase('Pending Orders') }}</a>
                    <a href="{{ route('admin.marketplace.orders.cancelled') }}" class="export_btn">{{ get_phrase('Cancelled Orders') }}</a>
                    <a href="{{ route('admin.marketplace.statistics') }}" class="export_btn">{{ get_phrase('Sales Statistics') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="table-responsive">
                <table class="table eTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ get_phrase('Order ID') }}</th>
                            <th scope="col">{{ get_phrase('Product') }}</th>
                            <th scope="col">{{ get_phrase('Buyer') }}</th>
                            <th scope="col">{{ get_phrase('Seller') }}</th>
                            <th scope="col">{{ get_phrase('Amount') }}</th>
                            <th scope="col">{{ get_phrase('Status') }}</th>
                            <th scope="col">{{ get_phrase('Date') }}</th>
                            <th scope="col" class="text-center">{{ get_phrase('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $key }}</td>
                            <td>#{{ $order->id }}</td>
                            <td>
                                @if($order->product)
                                    <div class="d-flex align-items-center">
                                        <span class="product-image me-2">
                                            <img src="{{ get_product_image($order->product->image, 'thumbnail') }}" 
                                                 width="40" height="40" class="rounded" alt="{{ $order->product->title }}">
                                        </span>
                                        <span class="product-title">{{ Str::limit($order->product->title, 30) }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">{{ get_phrase('Product removed') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->buyer)
                                    <div class="d-flex align-items-center">
                                        <span class="user-image me-2">
                                            <img src="{{ get_user_image($order->buyer->photo, 'optimized') }}" 
                                                 width="30" height="30" class="rounded-circle" alt="{{ $order->buyer->name }}">
                                        </span>
                                        <span class="user-name">{{ $order->buyer->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">{{ get_phrase('User removed') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->seller)
                                    <div class="d-flex align-items-center">
                                        <span class="user-image me-2">
                                            <img src="{{ get_user_image($order->seller->photo, 'optimized') }}" 
                                                 width="30" height="30" class="rounded-circle" alt="{{ $order->seller->name }}">
                                        </span>
                                        <span class="user-name">{{ $order->seller->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">{{ get_phrase('User removed') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->product && $order->product->getCurrency)
                                    {{ $order->product->getCurrency->symbol }} {{ number_format($order->amount, 2) }}
                                @else
                                    {{ number_format($order->amount, 2) }}
                                @endif
                            </td>
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
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="adminTable-action">
                                    <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ get_phrase('Actions') }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.marketplace.order.details', $order->id) }}">
                                                <i class="fas fa-eye"></i> {{ get_phrase('View Order') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="showAjaxModal('{{ route('load_modal_content', ['view_path' => 'backend.admin.marketplace.update_status_modal', 'order_id' => $order->id]) }}', '{{ get_phrase('Update Order Status') }}')">
                                                <i class="fas fa-edit"></i> {{ get_phrase('Update Status') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if(count($orders) == 0)
                        <tr>
                            <td colspan="9" class="text-center">{{ get_phrase('No orders found') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-end">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div> 
</div>
