<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'post_id',
        'user_id',
        'like_id',
        'amount'
    ];
    
    /**
     * Получить пост, к которому относится лайк
     */
    public function post()
    {
        return $this->belongsTo(Posts::class, 'post_id', 'post_id');
    }
    
    /**
     * Получить пользователя, который поставил лайк
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Получить тип лайка
     */
    public function like()
    {
        return $this->belongsTo(Like::class, 'like_id');
    }
}
