<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeBalance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'balance'
    ];
    
    /**
     * Получить пользователя, которому принадлежит этот баланс
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Получить транзакции этого баланса
     */
    public function transactions()
    {
        return $this->hasMany(LikeTransaction::class);
    }
    
    /**
     * Пополнить баланс
     * 
     * @param float $amount
     * @param string $type
     * @param string|null $description
     * @param array|null $metadata
     * @return LikeTransaction
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
     * Списать средства с баланса
     * 
     * @param float $amount
     * @param string $type
     * @param string $description
     * @param array $metadata
     * @return LikeTransaction|bool
     */
    public function withdraw($amount, $type, $description, $metadata = [])
    {
        // Проверяем, достаточно ли средств
        if ($this->balance < $amount) {
            return false;
        }
        
        // Конвертируем сумму в отрицательное значение для списания
        $amount = -abs($amount);
        
        // Обновляем баланс
        $this->balance += $amount;
        $this->save();
        
        // Создаем запись о транзакции
        return $this->transactions()->create([
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }
}
