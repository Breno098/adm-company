<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        Role::create(['name' => 'Ver Clientes',         'role' => 'index_client']);
        Role::create(['name' => 'Adicionar Cliente',    'role' => 'add_client']);
        Role::create(['name' => 'Visualizar Cliente',   'role' => 'show_client']);
        Role::create(['name' => 'Deletar Cliente',      'role' => 'delete_client']);
        Role::create(['name' => 'Atualizar Cliente',    'role' => 'update_client']);

        Role::create(['name' => 'Ver Produtos',         'role' => 'index_product']);
        Role::create(['name' => 'Adicionar Produto',    'role' => 'add_product']);
        Role::create(['name' => 'Visualizar Produto',   'role' => 'show_product']);
        Role::create(['name' => 'Deletar Produto',      'role' => 'delete_product']);
        Role::create(['name' => 'Atualizar Produto',    'role' => 'update_product']);

        Role::create(['name' => 'Ver Serviços',         'role' => 'index_service']);
        Role::create(['name' => 'Adicionar Serviço',    'role' => 'add_service']);
        Role::create(['name' => 'Visualizar Serviço',   'role' => 'show_service']);
        Role::create(['name' => 'Deletar Serviço',      'role' => 'delete_service']);
        Role::create(['name' => 'Atualizar Serviço',    'role' => 'update_service']);

        Role::create(['name' => 'Ver Categorias',         'role' => 'index_category']);
        Role::create(['name' => 'Adicionar Categoria',    'role' => 'add_category']);
        Role::create(['name' => 'Visualizar Categoria',   'role' => 'show_category']);
        Role::create(['name' => 'Deletar Categoria',      'role' => 'delete_category']);
        Role::create(['name' => 'Atualizar Categoria',    'role' => 'update_category']);

        Role::create(['name' => 'Ver Custos/Despesa',       'role' => 'index_expense']);
        Role::create(['name' => 'Adicionar Custo/Despesa',  'role' => 'add_expense']);
        Role::create(['name' => 'Visualizar Custo/Despesa', 'role' => 'show_expense']);
        Role::create(['name' => 'Deletar Custo/Despesa',    'role' => 'delete_expense']);
        Role::create(['name' => 'Atualizar Custo/Despesa',  'role' => 'update_expense']);

        Role::create(['name' => 'Ver Compromissos',         'role' => 'index_appointment']);
        Role::create(['name' => 'Adicionar Compromisso',    'role' => 'add_appointment']);
        Role::create(['name' => 'Visualizar Compromisso',   'role' => 'show_appointment']);
        Role::create(['name' => 'Deletar Compromisso',      'role' => 'delete_appointment']);
        Role::create(['name' => 'Atualizar Compromisso',    'role' => 'update_appointment']);

        Role::create(['name' => 'Ver Ordens',         'role' => 'index_order']);
        Role::create(['name' => 'Adicionar Ordem',    'role' => 'add_order']);
        Role::create(['name' => 'Visualizar Ordem',   'role' => 'show_order']);
        Role::create(['name' => 'Deletar Ordem',      'role' => 'delete_order']);
        Role::create(['name' => 'Atualizar Ordem',    'role' => 'update_order']);
    }
}
