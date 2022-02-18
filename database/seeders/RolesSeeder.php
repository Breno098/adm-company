<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function modules(): array
    {
        return [
            [
                'tag' => 'Usuário',
                'module' => 'user'
            ],
            [
                'tag' => 'Cliente',
                'module' => 'client'
            ],
            [
                'tag' => 'Funcionário',
                'module' => 'employee'
            ],
            [
                'tag' => 'Produto',
                'module' => 'product'
            ],
            [
                'tag' => 'Serviço',
                'module' => 'service'
            ],
            [
                'tag' => 'Custos/Despesa',
                'module' => 'expense'
            ],
            [
                'tag' => 'Compromissos',
                'module' => 'appointment'
            ],
            [
                'tag' => 'Pedido',
                'module' => 'order'
            ],
            [
                'tag' => 'Recibo de Funcionário',
                'module' => 'employee_receipt'
            ],
            [
                'tag' => 'Pedido',
                'module' => 'order_installment',
                'only' => ['add', 'delete', 'update'],
                'name' => 'Parcelas do Pedido'
            ],
            [
                'tag' => 'Pedido',
                'module' => 'order_product',
                'only' => ['add', 'delete', 'update'],
                'name' => 'Produtos do Pedido'
            ],
            [
                'tag' => 'Pedido',
                'module' => 'order_service',
                'only' => ['add', 'delete', 'update'],
                'name' => 'Serviços do Pedido'
            ],
        ];
    }

    public function operations(): array
    {
        return ['index', 'add', 'show', 'delete', 'update'];
    }

    public function name($operation, $module): string
    {
        $suffix = $module['name'] ?? $module['tag'];

        switch ($operation) {
            case 'index': return "Ver {$suffix}";
            case "add": return "Adicionar {$suffix}";
            case "show": return "Visualizar {$suffix}";
            case "delete": return "Deletar {$suffix}";
            case "update": return "Atualizar {$suffix}";
            default: return  "";
        }
    }

    public function runCruds()
    {
        foreach ($this->modules() as $module) {
            foreach ($module['only'] ?? $this->operations() as $operation) {
                Role::updateOrCreate([
                    'role' => "{$module['module']}_{$operation}",
                ],[
                    'name' => $this->name($operation, $module),
                    'tag' => $module['tag'],
                ]);
            }
        }
    }

    public function role($role, $name, $tag)
    {
        Role::updateOrCreate([
            'role' => $role,
        ],[
            'name' => $name,
            'tag' => $tag,
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->runCruds();

        $this->role('user_roles', 'Permissões para Usuário', 'Usuário');
        $this->role('report_index', 'Relatórios', 'Relatórios');
        $this->role('report_finance_index', 'Relatório Financeiro', 'Relatórios');
        $this->role('file_index', 'Arquivos', 'Arquivos');
        $this->role('employee_receipt_download', 'Recibo de Funcionário (Download)', 'Recibo de Funcionário');

        Role::get()->each(function ($role) {
            User::get()->each(function ($user) use ($role) {
                $user->roles()->attach($role);
            });
        });
    }
}
