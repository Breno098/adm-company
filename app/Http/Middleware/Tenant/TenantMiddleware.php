<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Tenant;
use App\Tenant\ManagerTenant;
use Closure;
use Illuminate\Http\Request;

class TenantMiddleware
{
    private ManagerTenant $managerTenant;

    public function __construct(ManagerTenant $managerTenant)
    {
        $this->managerTenant = $managerTenant;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = $this->getTenant($request->getHost());

        if (!$tenant) {
            abort(401, 'Tenant not found');
        }

        $this->managerTenant->setConnection($tenant);

        return $next($request);
    }

    /**
     * @param string $host
     *
     * @return Tenant|null
     */
    private function getTenant($host): ?Tenant
    {
        return Tenant::where('domain', $host)->first();
    }
}
