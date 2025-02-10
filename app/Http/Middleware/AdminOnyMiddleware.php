<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array(auth()->user()->role_id, [1, 2, 3])) {
            return $next($request);
        } else {
            // return abort(401);
            return redirect()->route('client-dashboard');
        }
    }
}
