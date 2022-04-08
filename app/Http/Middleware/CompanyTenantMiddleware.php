<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Company\Store\Storage;

class CompanyTenantMiddleware
{
        /**
     * @var Storage $storage
     */
    private Storage $storage;

    /**
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $company = $request->user()->company ?? null;

        if(!$company) {
            abort(403);
        }

        $this->storage->setFileSystemWithCompany($company);

        return $next($request);
    }
}
