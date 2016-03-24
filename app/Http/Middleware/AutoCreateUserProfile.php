<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AutoCreateUserProfile
{
    /**
     * create user profile if open_id is not exist
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = User::inSession();
        } catch (ModelNotFoundException $e) {
            if (session()->has('wechat.oauth_user')) {
                User::create(['open_id' => session('wechat.oauth_user')->id]);
            }
        }
        return $next($request);
    }
}
