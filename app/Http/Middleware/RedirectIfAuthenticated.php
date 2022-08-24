<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard === "admin" && auth($guard)->check()) {
            return redirect(route('admin.index'));
        }

        if ($guard === "user" && auth($guard)->check()) {
            return redirect(route('index'));
        }

        return $next($request);
    }
}
