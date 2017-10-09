<?php

namespace App\Http\Middleware;

use App\Models\User\Activity;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class LogActivity
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $activity = new Activity();
        if($user){
            $activity->user_id = $user->id;
        }
        $activity->path = $request->getPathInfo();
        $activity->method = $request->getMethod();
        $activity->ip_address = $request->getClientIp();
        $activity->save();

        return $next($request);
    }
}
