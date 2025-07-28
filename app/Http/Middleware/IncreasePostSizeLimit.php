<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncreasePostSizeLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        ini_set('post_max_size', '25M');
        ini_set('upload_max_filesize', '30M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '300');

        return $next($request);
    }
}
