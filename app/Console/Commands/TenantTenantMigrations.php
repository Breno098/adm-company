<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Tenant\ManagerTenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantTenantMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {--refresh} {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Migrations Tenants';

    private ManagerTenant $managerTenant;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ManagerTenant $managerTenant)
    {
        parent::__construct();

        $this->managerTenant = $managerTenant;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($id = $this->argument('id')) {
            $tenant = Tenant::find($id);

            if($tenant) {
                $this->execCommand($tenant);
            }

            return;
        }

        $tenants = Tenant::get();

        foreach ($tenants as $tenant) {
            $this->execCommand($tenant);
        }
    }

    private function execCommand(Tenant $tenant)
    {
        $command = $this->option('refresh') ? 'migrate:refresh' : 'migrate';

        $this->managerTenant->setConnection($tenant);

        $this->info("Connecting Tenant {$tenant->name}");

        Artisan::call($command, [
            '--force' => true,
            '--path' => '/database/migrations/tenant'
        ]);

        $this->info("End Connecting Tenant {$tenant->name}");
        $this->info("--------------------------------------");
    }
}
