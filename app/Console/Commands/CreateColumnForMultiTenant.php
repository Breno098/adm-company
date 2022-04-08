<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateColumnForMultiTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:column-multitenant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificação e criação de coluns company_id em tabelas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tables = [
            'addresses',
            'appointments',
            'bank_accounts',
            'category_expense',
            'category_item',
            'clients',
            'contacts',
            'employee_receipts',
            'employees',
            'expenses',
            'image_item',
            'images',
            'installments',
            'item_order',
            'items',
            'logs',
            'order_payment',
            'orders',
            'payments',
            'positions',
            'role_user',
            'roles',
        ];

        foreach ($tables as $table) {
            $this->info("Table: {$table}");

            if(!Schema::hasColumn($table, 'company_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('company_id')->nullable()->constrained();
                });

                $this->info("Create column company_id | Table: {$table}");
            }

            $rows = DB::table($table)->get();

            foreach ($rows as $row) {
                DB::table($table)->where('id', $row->id)->update(['company_id' => 1]);
            }
        }

        $rows = DB::table('users')->get();

        foreach ($rows as $row) {
            DB::table('users')->where('id', $row->id)->update(['company_id' => 1]);
        }

        return 0;
    }
}
