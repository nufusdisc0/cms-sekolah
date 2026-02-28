<?php

namespace App\Http\Middleware;

use App\Models\IPBan;
use Closure;
use Illuminate\Http\Request;

class CheckIPBan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only check on login and password reset routes
        if ($request->routeIs('login', 'password.request', 'password.reset')) {
            $ip = $request->ip();

            if (IPBan::isBanned($ip)) {
                return redirect('/')
                    ->with('error', 'Your IP address has been temporarily banned due to multiple failed login attempts. Please try again later.');
            }
        }

        return $next($request);
    }
}
