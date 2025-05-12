<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, есть ли уже активный язык в сессии
        if (Session::has('active_language')) {
            $language = Session::get('active_language');
        } else {
            // Получаем язык по умолчанию из настроек
            // $languageSetting = DB::table('settings')->where('type', 'system_language')->first();
            // dd($languageSetting);
            // $language = $languageSetting ? $languageSetting->description : 'uz'; // По умолчанию используем узбекский язык
            $language = 'uz'; // По умолчанию используем узбекский язык
            
            // Сохраняем язык в сессии
            Session::put('active_language', $language);
        }

        // Устанавливаем локаль приложения
        App::setLocale($language);

        return $next($request);
    }
} 