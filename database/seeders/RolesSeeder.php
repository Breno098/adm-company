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
                'tag' => 'Categoria',
                'module' => 'category'
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
                'tag' => 'Parcela',
                'module' => 'installment',
                'only' => ['add', 'delete', 'update']
            ],
        ];
    }

    public function operations(): array
    {
        return ['index', 'add', 'show', 'delete', 'update'];
    }

    public function name($operation, $tag): string
    {
        switch ($operation) {
            case 'index': return "Ver {$tag}";
            case "add": return "Adicionar {$tag}";
            case "show": return "Visualizar {$tag}";
            case "delete": return "Deletar {$tag}";
            case "update": return "Atualizar {$tag}";
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
                    'name' => $this->name($operation, $module['tag']),
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
    }
}
