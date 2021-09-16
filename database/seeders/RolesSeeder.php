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
        Role::create([
            'name' => 'Permissões para Usuário',
            'role' => 'user_roles',
            'tag' => 'Usuário',
        ]);

        Role::create([
            'name' => 'Ver Usuários',
            'role' => 'user_index',
            'tag' => 'Usuário',
            'description' => 'Usuário poderá ver os demais usuários de sistema.'
        ]);

        Role::create([
            'name' => 'Adicionar Usuário',
            'role' => 'user_add',
            'tag' => 'Usuário',
            'description' => 'Usuário poderá adicionar outros usuários no sistema.'
        ]);

        Role::create([
            'name' => 'Visualizar Usuário',
            'role' => 'user_show',
            'tag' => 'Usuário',
            'description' => 'Usuário poderá visualizar as informações dos demais usuários.'
        ]);

        Role::create([
            'name' => 'Deletar Usuário',
            'role' => 'user_delete',
            'tag' => 'Usuário',
            'description' => 'Usuário poderá deletar usuários de sistema.'
        ]);

        Role::create([
            'name' => 'Atualizar Usuário',
            'role' => 'user_update',
            'tag' => 'Usuário',
            'description' => 'Usuário poderá atualizar usuários'
        ]);

        Role::create([
            'name' => 'Ver Clientes',
            'role' => 'client_index',
            'tag' => 'Cliente',
        ]);

        Role::create([
            'name' => 'Adicionar Cliente',
            'role' => 'client_add',
            'tag' => 'Cliente',
        ]);

        Role::create([
            'name' => 'Visualizar Cliente',
            'role' => 'client_show',
            'tag' => 'Cliente',
        ]);

        Role::create([
            'name' => 'Deletar Cliente',
            'role' => 'client_delete',
            'tag' => 'Cliente',
        ]);

        Role::create([
            'name' => 'Atualizar Cliente',
            'role' => 'client_update',
            'tag' => 'Cliente',
        ]);


        Role::create([
            'name' => 'Ver Produtos',
            'role' => 'product_index',
            'tag' => 'Produto',
        ]);

        Role::create([
            'name' => 'Adicionar Produto',
            'role' => 'product_add',
            'tag' => 'Produto',
        ]);

        Role::create([
            'name' => 'Visualizar Produto',
            'role' => 'product_show',
            'tag' => 'Produto',
        ]);

        Role::create([
            'name' => 'Deletar Produto',
            'role' => 'product_delete',
            'tag' => 'Produto',
        ]);

        Role::create([
            'name' => 'Atualizar Produto',
            'role' => 'product_update',
            'tag' => 'Produto',
        ]);


        Role::create([
            'name' => 'Ver Serviços',
            'role' => 'service_index',
            'tag' => 'Serviço',
        ]);

        Role::create([
            'name' => 'Adicionar Serviço',
            'role' => 'service_add',
            'tag' => 'Serviço',
        ]);

        Role::create([
            'name' => 'Visualizar Serviço',
            'role' => 'service_show',
            'tag' => 'Serviço',
        ]);

        Role::create([
            'name' => 'Deletar Serviço',
            'role' => 'service_delete',
            'tag' => 'Serviço',
        ]);

        Role::create([
            'name' => 'Atualizar Serviço',
            'role' => 'service_update',
            'tag' => 'Serviço',
        ]);


        Role::create([
            'name' => 'Ver Categorias',
            'role' => 'category_index',
            'tag' => 'Categoria',
        ]);

        Role::create([
            'name' => 'Adicionar Categoria',
            'role' => 'category_add',
            'tag' => 'Categoria',
        ]);

        Role::create([
            'name' => 'Visualizar Categoria',
            'role' => 'category_show',
            'tag' => 'Categoria',
        ]);

        Role::create([
            'name' => 'Deletar Categoria',
            'role' => 'category_delete',
            'tag' => 'Categoria',
        ]);

        Role::create([
            'name' => 'Atualizar Categoria',
            'role' => 'category_update',
            'tag' => 'Categoria',
        ]);


        Role::create([
            'name' => 'Ver Custos/Despesa',       
            'role' => 'expense_index',
            'tag' => 'Custos',
        ]);

        Role::create([
            'name' => 'Adicionar Custo/Despesa',  
            'role' => 'expense_add',
            'tag' => 'Custos',
        ]);

        Role::create([
            'name' => 'Visualizar Custo/Despesa', 
            'role' => 'expense_show',
            'tag' => 'Custos',
        ]);

        Role::create([
            'name' => 'Deletar Custo/Despesa',    
            'role' => 'expense_delete',
            'tag' => 'Custos',
        ]);

        Role::create([
            'name' => 'Atualizar Custo/Despesa',  
            'role' => 'expense_update',
            'tag' => 'Custos',
        ]);


        Role::create([
            'name' => 'Ver Compromissos',
            'role' => 'appointment_index',
            'tag' => 'Compromissos',
        ]);

        Role::create([
            'name' => 'Adicionar Compromisso',
            'role' => 'appointment_add',
            'tag' => 'Compromissos',
        ]);

        Role::create([
            'name' => 'Visualizar Compromisso',
            'role' => 'appointment_show',
            'tag' => 'Compromissos',
        ]);

        Role::create([
            'name' => 'Deletar Compromisso',
            'role' => 'appointment_delete',
            'tag' => 'Compromissos',
        ]);

        Role::create([
            'name' => 'Atualizar Compromisso',
            'role' => 'appointment_update',
            'tag' => 'Compromissos',
        ]);


        Role::create([
            'name' => 'Ver Ordens',
            'role' => 'order_index',
            'tag' => 'Ordem',
        ]);

        Role::create([
            'name' => 'Adicionar Ordem',
            'role' => 'order_add',
            'tag' => 'Ordem',
        ]);

        Role::create([
            'name' => 'Visualizar Ordem',
            'role' => 'order_show',
            'tag' => 'Ordem',
        ]);

        Role::create([
            'name' => 'Deletar Ordem',
            'role' => 'order_delete',
            'tag' => 'Ordem',
        ]);

        Role::create([
            'name' => 'Atualizar Ordem',
            'role' => 'order_update',
            'tag' => 'Ordem',
        ]);


        User::all()->map(function($user){
            Role::all()->map(function($role) use ($user){
                $user->roles()->attach($role);
            }); 
        });
    }
}
