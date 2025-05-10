<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeProduct extends Model
{
	protected $table = 'free_products';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false; // Agar created_at va updated_at bo‘lmasa

	protected $fillable = [
		'product_type_id',
		'sponsor_id',
		'receiver_id',
		'is_payment_sponsor',
		'is_sold',
		'is_active',
		'is_deleted',
		'deadline_hour',
		'delivered_date',
		'created_date',
	];
}