<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'balance'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
    
    /**
     * Add funds to wallet
     */
    public function deposit($amount, $type, $description = null, $metadata = null)
    {
        $this->balance += $amount;
        $this->save();
        
        return $this->transactions()->create([
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'metadata' => $metadata ? json_encode($metadata) : null
        ]);
    }

    /**
     * Withdraw amount from wallet
     * 
     * @param float $amount
     * @param string $type
     * @param string $description
     * @param array $metadata
     * @return WalletTransaction
     */
    public function withdraw($amount, $type, $description, $metadata = [])
    {
        // Convert amount to negative for withdrawal
        $amount = -abs($amount);
        
        // Update wallet balance
        $this->balance += $amount;
        $this->save();
        
        // Create transaction record
        return WalletTransaction::create([
            'wallet_id' => $this->id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }
} 