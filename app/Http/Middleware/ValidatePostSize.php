<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePostSize
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Увеличиваем лимит размера запроса
        $max = env('POST_MAX_SIZE', '100M');
        $size = $this->getPostMaxSize();
        
        if ($size > 0 && $request->server('CONTENT_LENGTH') > $size) {
            return response('Upload size exceeded.', 413);
        }

        return $next($request);
    }

    /**
     * Determine the server's post_max_size value.
     */
    protected function getPostMaxSize(): int
    {
        $max = env('POST_MAX_SIZE', '100M');
        
        if (is_numeric($max)) {
            return (int) $max;
        }

        $metric = strtoupper(substr($max, -1));
        $max = (int) $max;

        switch ($metric) {
            case 'K':
                return $max * 1024;
            case 'M':
                return $max * 1048576;
            case 'G':
                return $max * 1073741824;
            default:
                return $max;
        }
    }
} 