<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientOnlyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array(auth()->user()->role_id, [4])) {
            return $next($request);
        } else {
            // return abort(401);
            return redirect()->route('dashboard');
        }
    }
}
