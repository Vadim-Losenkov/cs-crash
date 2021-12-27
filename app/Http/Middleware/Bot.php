<?php

namespace App\Http\Middleware;

use Closure;

class Bot
{
    const SECRET_KEY = 'cZN^ZH8)mu~9e,>6M>3qKV=Ar^fFF,7/';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->get('secretKey') !== self::SECRET_KEY) return response()->json('Invalid Request');
        return $next($request);
    }
}
