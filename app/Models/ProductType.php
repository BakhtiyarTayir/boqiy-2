<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
	protected $table = 'product_types';

	protected $primaryKey = 'id';
	
	public $timestamps = false; // Agar created_at va updated_at bo'lmasa
	
	protected $fillable = [
		'name',
		'price_for_sponsor',
		'price_for_every_one',
		'text_for_sponsor',
		'text_for_every_one',
		'file_path',
		'is_deleted',
		'created_date',
	];
    
    /**
     * Получить цену спонсора без лишних десятичных нулей
     */
    public function getPriceForSponsorFormattedAttribute()
    {
        return number_format($this->price_for_sponsor, 0, '.', ' ');
    }
    
    /**
     * Получить цену для всех без лишних десятичных нулей
     */
    public function getPriceForEveryOneFormattedAttribute()
    {
        return number_format($this->price_for_every_one, 0, '.', ' ');
    }
}