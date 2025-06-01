<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'type',
        'metadata'
    ];
    
    /**
     * Аттрибуты для приведения типов
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
    ];
    
    /**
     * Пользователь, которому принадлежит заказ
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Создает новый заказ на пополнение баланса лайков
     *
     * @param int $userId
     * @param float $amount
     * @param string $paymentMethod
     * @param array $metadata
     * @return Order
     */
    public static function createLikeBalanceTopup($userId, $amount, $paymentMethod, $metadata = [])
    {
        $orderId = 'LB' . time() . rand(1000, 9999);
        
        return self::create([
            'order_id' => $orderId,
            'user_id' => $userId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'type' => 'like_balance_topup',
            'metadata' => $metadata
        ]);
    }
    
    /**
     * Обновляет статус заказа
     *
     * @param string $status
     * @param string|null $transactionId
     * @return bool
     */
    public function updateStatus($status, $transactionId = null)
    {
        $this->status = $status;
        
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        
        return $this->save();
    }
} 