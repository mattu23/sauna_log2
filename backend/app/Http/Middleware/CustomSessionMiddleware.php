<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomSessionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
