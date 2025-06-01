<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'amount',
        'status',
        'shipping_address',
        'notes',
        'completed_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'amount' => 'float',
    ];
    
    /**
     * Get the product for this order
     */
    public function product()
    {
        return $this->belongsTo(Marketplace::class, 'product_id');
    }
    
    /**
     * Get the buyer for this order
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    
    /**
     * Get the seller for this order
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
