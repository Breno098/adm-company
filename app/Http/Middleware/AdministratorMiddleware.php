<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdministratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->userLoggedIsAdmin()) {
            return $next($request);
        }

        return response()->json(['error' => 'Permission denied'], 400);
    }

    private function userLoggedIsAdmin()
    {
        return auth()->user()->admin;
    }
}
