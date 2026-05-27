<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('msg', 'Please login first.');
        }

        $user = Auth::user();

        if (
            $user
            && (bool) ($user->is_temp_password ?? false)
            && ! $request->routeIs('student.change-password.show', 'student.change-password.store', 'student.change-password.update')
        ) {
            return redirect()->route('student.change-password.show');
        }

        return $next($request);
    }
}
