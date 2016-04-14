<?php

namespace App\Http\Middleware;

use App\Seller;
use App\User;
use Closure;

class AutoCreateSellerProfile
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
        $user = User::inSession();
        if (!$user->seller) {
            // create new seller profile for user
            $seller = new Seller;
            $seller->user()->associate($user);
            $seller->save();
        }
        return $next($request);
    }
}
