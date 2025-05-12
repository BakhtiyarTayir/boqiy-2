<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeTransaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'like_balance_id',
        'user_id',
        'amount',
        'type',
        'description',
        'metadata'
    ];
    
    /**
     * Получить баланс лайков, к которому относится эта транзакция
     */
    public function likeBalance()
    {
        return $this->belongsTo(LikeBalance::class);
    }
    
    /**
     * Получить пользователя, к которому относится эта транзакция
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Атрибуты, которые должны быть приведены к определенным типам.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
    ];
}
