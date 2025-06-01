<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize as BaseValidatePostSize;

class CustomValidatePostSize extends BaseValidatePostSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $max = $this->getPostMaxSize();

        if ($max > 0 && $request->server('CONTENT_LENGTH') > $max) {
            // Вместо исключения просто логируем и пропускаем
            \Log::warning('Large post detected: ' . $request->server('CONTENT_LENGTH') . ' bytes');
        }

        return $next($request);
    }

    /**
     * Переопределяем метод для получения максимального размера POST-запроса
     *
     * @return int
     */
    protected function getPostMaxSize()
    {
        $postMaxSize = ini_get('post_max_size');
        
        // Удаляем пробелы из строки
        $postMaxSize = trim($postMaxSize);
        
        if (is_numeric($postMaxSize)) {
            return (int) $postMaxSize;
        }
        
        $metric = strtoupper(substr($postMaxSize, -1));
        $postMaxSize = (int) $postMaxSize;
        
        switch ($metric) {
            case 'K':
                return $postMaxSize * 1024;
            case 'M':
                return $postMaxSize * 1048576;
            case 'G':
                return $postMaxSize * 1073741824;
            default:
                return $postMaxSize;
        }
    }
} 