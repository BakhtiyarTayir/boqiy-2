<?php

namespace App\Http\Controllers\Auth;

use App\Models\FreeProduct;
use App\Models\Marketplace;
use App\Models\Posts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB as DataBase;

class HelperRegisterController
{
	public function noLogin()
	{
		$user = auth()->user();

		//First 10 posts
		$posts = Posts::query()
			->join('users', 'posts.user_id', '=', 'users.id')
			->where('posts.status', 'active')
			->where('posts.report_status', '0')
			->where('posts.publisher', '!=', 'paid_content') // Post turi pullik bo'lmasligi kerak
			->where(function (Builder $query) {
				$query->where('posts.publisher', '!=', 'video_and_shorts')
					->orWhere(function (Builder $query2) {
						$query2->join('group_members', function (JoinClause $join) {
							$join->on('posts.publisher_id', '=', 'group_members.group_id')
								->where('posts.publisher', '=', 'group')
								->where('group_members.user_id', '=', auth()->id());
						});
					});
			})
			->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
			->orderBy('posts.post_id', 'DESC')
			->take(15)
			->get();

		$page_data['posts'] = $posts;

	//	$page_data['userWallet'] = $user->wallet;
	//	$page_data['likeBalance'] = $user->likeBalance;

		return view('no-login', $page_data);
	}
	
	public function noLoginProduct()
	{
		$page_data['products'] =  $freeProducts = DataBase::table('free_products')
			->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
			->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
			->leftJoin('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
			->select(
				'free_products.*',
				'pt.id as product_type_id',
				'pt.name as name',
				'pt.price_for_every_one as price_for_every_one',
				'pt.file_path as file_path',
				'sponsors.name as sponsor_name',
				'receivers.name as receiver_name'
			)
			->where('free_products.is_payment_sponsor', 1)
			->where('free_products.is_sold', 0)
			->get();

		$page_data['view_path'] = 'frontend.marketplace.products';

		return view('no-login-products', $page_data);
	}
}