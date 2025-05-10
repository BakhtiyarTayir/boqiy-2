<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarketplaceOrder;
use App\Models\Marketplace;
use App\Models\User;
use Session;
use DB;

class MarketplaceOrderController extends Controller
{
    /**
     * Display a listing of the orders
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_data = [
            'orders' => MarketplaceOrder::orderBy('created_at', 'desc')->paginate(20),
            'page_name' => 'marketplace_orders',
            'page_title' => get_phrase('Marketplace Orders'),
            'view_path' => 'marketplace/orders'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Display details of a specific order
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = MarketplaceOrder::with(['buyer', 'seller', 'product'])->findOrFail($id);
        
        $page_data = [
            'order' => $order,
            'page_name' => 'marketplace_order_details',
            'page_title' => get_phrase('Order Details'),
            'view_path' => 'marketplace/order_details'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Update the status of an order
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $order = MarketplaceOrder::findOrFail($id);
        $order->status = $request->status;
        
        if ($request->status == 'completed' && $order->completed_at == null) {
            $order->completed_at = now();
        }
        
        $order->save();
        
        Session::flash('success_message', get_phrase('Order status updated successfully'));
        return redirect()->back();
    }
    
    /**
     * Get sales statistics
     *
     * @return \Illuminate\Http\Response
     */
    public function salesStatistics()
    {
        // Today's sales
        $today_sales = MarketplaceOrder::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('amount');
            
        // This week's sales
        $week_sales = MarketplaceOrder::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'completed')
            ->sum('amount');
            
        // This month's sales
        $month_sales = MarketplaceOrder::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('amount');
            
        // All time sales
        $all_time_sales = MarketplaceOrder::where('status', 'completed')
            ->sum('amount');
            
        // Get top selling products
        $top_products = DB::table('marketplace_orders')
            ->join('marketplaces', 'marketplace_orders.product_id', '=', 'marketplaces.id')
            ->select('marketplaces.id', 'marketplaces.title', 'marketplaces.image', DB::raw('count(*) as sales_count'), DB::raw('sum(marketplace_orders.amount) as total_sales'))
            ->where('marketplace_orders.status', 'completed')
            ->groupBy('marketplaces.id', 'marketplaces.title', 'marketplaces.image')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();
            
        // Get top sellers
        $top_sellers = DB::table('marketplace_orders')
            ->join('users', 'marketplace_orders.seller_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.photo', DB::raw('count(*) as sales_count'), DB::raw('sum(marketplace_orders.amount) as total_sales'))
            ->where('marketplace_orders.status', 'completed')
            ->groupBy('users.id', 'users.name', 'users.photo')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();
            
        $page_data = [
            'today_sales' => $today_sales,
            'week_sales' => $week_sales,
            'month_sales' => $month_sales,
            'all_time_sales' => $all_time_sales,
            'top_products' => $top_products,
            'top_sellers' => $top_sellers,
            'page_name' => 'marketplace_sales_statistics',
            'page_title' => get_phrase('Sales Statistics'),
            'view_path' => 'marketplace/sales_statistics'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Display all cancelled orders
     * 
     * @return \Illuminate\Http\Response
     */
    public function cancelledOrders()
    {
        $page_data = [
            'orders' => MarketplaceOrder::where('status', 'cancelled')
                ->orderBy('created_at', 'desc')
                ->paginate(20),
            'page_name' => 'marketplace_cancelled_orders',
            'page_title' => get_phrase('Cancelled Orders'),
            'view_path' => 'marketplace/orders'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Display pending orders
     * 
     * @return \Illuminate\Http\Response
     */
    public function pendingOrders()
    {
        $page_data = [
            'orders' => MarketplaceOrder::where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(20),
            'page_name' => 'marketplace_pending_orders',
            'page_title' => get_phrase('Pending Orders'),
            'view_path' => 'marketplace/orders'
        ];
        
        return view('backend.index', $page_data);
    }
}
