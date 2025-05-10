<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's purchase history
     */
    public function purchaseHistory()
    {
        $user = Auth::user();
        $orders = MarketplaceOrder::with(['seller', 'product'])
            ->where('buyer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('frontend.marketplace.purchase_history', compact('orders'));
    }

    /**
     * Display user's sold items
     */
    public function soldItems()
    {
        $user = Auth::user();
        $orders = MarketplaceOrder::with(['buyer', 'product'])
            ->where('seller_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('frontend.marketplace.sold_items', compact('orders'));
    }
} 