<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminFullAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        // Check if user is authenticated and has admin role
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Check if user has admin role (assuming role with name 'admin')
                $adminRole = $user->roles->firstWhere('name', 'admin');
                
                if ($adminRole) {
                    // Define all permissions for admin users by allowing all gates
                    // This will override any subsequent permission checks
                    Gate::before(function ($user, $ability) {
                        // Allow all permissions for admin users
                        return true;
                    });
                    
                    break; // Exit the loop once we confirm admin status
                }
            }
        }

        return $next($request);
    }
}