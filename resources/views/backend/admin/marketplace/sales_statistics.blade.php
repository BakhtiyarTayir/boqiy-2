<!-- Sales Statistics -->
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Sales Statistics') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="{{ route('admin.dashboard') }}">{{ get_phrase('Dashboard') }}</a></li>
                        <li><a href="#">{{ get_phrase('Marketplace') }}</a></li>
                        <li><a href="#">{{ get_phrase('Sales Statistics') }}</a></li>
                    </ul>
                </div>
                <div class="export-btn-area">
                    <a href="{{ route('admin.marketplace.orders') }}" class="export_btn">{{ get_phrase('All Orders') }}</a>
                    <a href="{{ route('admin.marketplace.orders.pending') }}" class="export_btn">{{ get_phrase('Pending Orders') }}</a>
                    <a href="{{ route('admin.marketplace.orders.cancelled') }}" class="export_btn">{{ get_phrase('Cancelled Orders') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sales Summary Cards -->
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="row">
                <!-- Today Sales -->
                <div class="col-md-3">
                    <div class="card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ get_phrase('Today') }}</h5>
                            <h2 class="card-text">{{ number_format($today_sales, 2) }}</h2>
                            <p class="text-muted">{{ get_phrase('Sales Today') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- This Week Sales -->
                <div class="col-md-3">
                    <div class="card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ get_phrase('This Week') }}</h5>
                            <h2 class="card-text">{{ number_format($week_sales, 2) }}</h2>
                            <p class="text-muted">{{ get_phrase('Sales This Week') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- This Month Sales -->
                <div class="col-md-3">
                    <div class="card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ get_phrase('This Month') }}</h5>
                            <h2 class="card-text">{{ number_format($month_sales, 2) }}</h2>
                            <p class="text-muted">{{ get_phrase('Sales This Month') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- All Time Sales -->
                <div class="col-md-3">
                    <div class="card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ get_phrase('All Time') }}</h5>
                            <h2 class="card-text">{{ number_format($all_time_sales, 2) }}</h2>
                            <p class="text-muted">{{ get_phrase('Total Sales') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-md-6">
        <div class="eSection-wrap mt-4">
            <h4 class="pb-3">{{ get_phrase('Top Selling Products') }}</h4>
            
            <div class="table-responsive">
                <table class="table eTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ get_phrase('Product') }}</th>
                            <th scope="col">{{ get_phrase('Sales Count') }}</th>
                            <th scope="col">{{ get_phrase('Total Sales') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top_products as $key => $product)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="product-image me-2">
                                        <img src="{{ get_product_image($product->image, 'thumbnail') }}" 
                                             width="40" height="40" class="rounded" alt="{{ $product->title }}">
                                    </span>
                                    <span class="product-title">{{ Str::limit($product->title, 20) }}</span>
                                </div>
                            </td>
                            <td>{{ $product->sales_count }}</td>
                            <td>{{ number_format($product->total_sales, 2) }}</td>
                        </tr>
                        @endforeach
                        @if(count($top_products) == 0)
                        <tr>
                            <td colspan="4" class="text-center">{{ get_phrase('No products sold yet') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Top Sellers -->
    <div class="col-md-6">
        <div class="eSection-wrap mt-4">
            <h4 class="pb-3">{{ get_phrase('Top Sellers') }}</h4>
            
            <div class="table-responsive">
                <table class="table eTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ get_phrase('Seller') }}</th>
                            <th scope="col">{{ get_phrase('Sales Count') }}</th>
                            <th scope="col">{{ get_phrase('Total Sales') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top_sellers as $key => $seller)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="user-image me-2">
                                        <img src="{{ get_user_image($seller->photo, 'optimized') }}" 
                                             width="30" height="30" class="rounded-circle" alt="{{ $seller->name }}">
                                    </span>
                                    <span class="user-name">{{ $seller->name }}</span>
                                </div>
                            </td>
                            <td>{{ $seller->sales_count }}</td>
                            <td>{{ number_format($seller->total_sales, 2) }}</td>
                        </tr>
                        @endforeach
                        @if(count($top_sellers) == 0)
                        <tr>
                            <td colspan="4" class="text-center">{{ get_phrase('No sellers yet') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.sales-card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.sales-card:hover {
    transform: translateY(-5px);
}
.sales-card .card-title {
    color: #6c757d;
    font-size: 16px;
    margin-bottom: 10px;
}
.sales-card .card-text {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 5px;
}
</style> 