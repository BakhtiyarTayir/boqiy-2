<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserSession
{
	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);

		$isAuth = Auth::check();

		if (!$isAuth) {
			if ($request->url() == route('no-login')
				|| in_array(url()->current(), [
					route('login'),
					route('register'),
					route('term.view'),
					route('noLoginProduct'),
				])
				|| $request->is(route('showProductTypeFile', '*'))
			) {
				return $response;
			}

			return redirect(route('no-login'));
		}

		return $response;
	}
}

