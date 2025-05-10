<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'price',
        'animation_path',
        'is_active'
    ];
    
    /**
     * Получить все лайки, поставленные с использованием этого типа лайка
     */
    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }
    
    /**
     * Получить URL изображения лайка
     */
    public function getImageUrlAttribute()
    {
        if ($this->animation_path) {
            return asset('storage/likes/' . $this->animation_path);
        }
        
        // Возвращаем URL изображения по умолчанию, если путь не указан
        return asset('storage/images/gift-icon.svg');
    }
}
