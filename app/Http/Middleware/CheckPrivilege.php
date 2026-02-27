<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\UserPrivilege;

class CheckPrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $moduleName = null): Response
    {
        $user = Auth::user();

        // Super users can implicitly access everything
        if ($user && $user->user_type === 'super_user') {
            return $next($request);
        }

        if (!$user) {
            return redirect('login');
        }

        if ($moduleName) {
            $module = Module::where('module_name', $moduleName)
                ->orWhere('module_url', $moduleName)
                ->first();

            if ($module) {
                $hasPrivilege = UserPrivilege::where('user_group_id', $user->user_group_id)
                    ->where('module_id', $module->id)
                    ->exists();

                if (!$hasPrivilege) {
                    abort(403, 'Unauthorized Access to Module: ' . $moduleName);
                }
            }
        }

        return $next($request);
    }
}
