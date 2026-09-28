<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cache;
use Carbon\Carbon;

class IsMember
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
        if (Auth::check() && Auth::user()->user_type == 'member') {

            $expiresAt = Carbon::now()->addMinutes(3);
            Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);

             if(Auth::user()->approved == 0)
             {
                auth()->logout();
                flash(translate("Please wait for admin approval"));
                return redirect()->route('user.login');
             }
             else {
                 if(Auth::user()->blocked == 1)
                 {
                     return redirect()->route('user.blocked');
                 }
                 else {
                     return $next($request);
                 }
             }
        }
        else{
            session(['link' => url()->current()]);
            return redirect()->route('user.login');
        }
    }
}
