<?php

namespace App\Http\Middleware;

use App\Interfaces\InternalCode;
use Closure;
use Illuminate\Http\Request;

class DeadlineMiddleware
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
        return $next($request);
        
        if ($this->verifyDeadlineByCompany()) {
            return $next($request);
        }

        return response()->json([
            'error' => 'User expiration date',
            'internal_code' => InternalCode::ERROR_DEADLINE
        ]);
    }

    private function verifyDeadlineByCompany()
    {
        return now() < auth()->user()->company->deadline;
    }
}
