<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'company_name',
        'phone_number',
        'instagram',
        'telegram',
        'youtube',
        'twitter',
        'facebook',
        'sponsor_image',
        'views_purchased'
    ];

    public function product()
    {
        return $this->belongsTo(Marketplace::class, 'product_id');
    }
} 