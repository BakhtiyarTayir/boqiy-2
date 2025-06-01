<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
	use HasFactory;

	const TYPE_TEXT = 1;
	const TYPE_FILE = 2;

	const PRODUCT_COMMENT_TYPES = [
		self::TYPE_TEXT => 'Text',
		self::TYPE_FILE => 'File',
	];

	public $timestamps = false;

	protected $primaryKey = 'id';
	
	protected $table = 'product_comments';

	protected $fillable = [
		'user_id',
		'product_id',
		'type',
		'text',
		'file_path',
		'is_deleted',
		'created_date',
		'update_date',
	];
}