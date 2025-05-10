<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
	protected $table = 'product_types';

	protected $primaryKey = 'id';
	
	public $timestamps = false; // Agar created_at va updated_at bo‘lmasa
	
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
}