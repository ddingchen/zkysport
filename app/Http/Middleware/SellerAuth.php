<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class SellerAuth
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
        if (!User::inSession()->realname) {
            return redirect('/sell/auth');
        }
        return $next($request);
    }
}
