# ADM Company

Este aplicativo trata se de um aplicativo para cadastros e organização de clientes, produtos, serviços, compromissos, custos, orçamentos, ordens de serviços e demais atividades financeiras de uma empresa.  


## Instalação

### Clonando e instalando dependências

Clone o repositório

    git clone https://github.com/Breno098/adm-company.git

Navegue até a pasta do projeto:

    cd .\adm-company

Faça a instalação das dependencias PHP com o comando:

    composer install

Para as dependencias JavaScript, utilize:

    npm install

### Comandos para execução e construção

Copie o arquivo env de exemplo e faça as alterações de configuração necessárias no arquivo .env

    cp .env.example .env

Gerar uma nova chave de aplicativo

    php artisan key:generate

Execute as migrações do banco de dados (defina a conexão do banco de dados em .env antes de migrar)

    php artisan migrate

Inicie o servidor de desenvolvimento local

    php artisan serve

Construa e execute o projeto com o comando

    npm run watch 
    // or
    npm run dev

Agora você pode acessar o servidor em http: // localhost:8000

## Populando banco de dados

Crie um banco de dados e adicione as informações no arquivo de variáveis de ambiente (.env).

    DB_DATABASE=NOME_DO_BANCO_DE_DADOS
    DB_USERNAME=USUARIO
    DB_PASSWORD=SENHA

Para testes, preencha os dados das tabelas de clients, items, addresses e demais tabelas do aplicativo. Execute o comando:

    php artisan db:seed

## Visão geral do código

### Dependências

- [Vue](https://vuejs.org/)
- [Vuetify](https://vuetifyjs.com/en/)
- [Vue Router](https://router.vuejs.org/)
- [Vuex](https://vuex.vuejs.org/)
- [Vue-meta](https://vue-meta.nuxtjs.org/)
- [V-mask](https://www.npmjs.com/package/v-mask)
- [Moment.js](https://momentjs.com/)
- [date-fns](https://date-fns.org/)

### Pastas

- `app` - Contém modelos Eloquent
- `app/Http/Controllers` - Contém controladores
- `app/Models` - Contém modelos/entidades
- `app/Services` - Contém serviços e utilitários
- `config` - Contém todos os arquivos de configuração do projeto
- `database/factories` - Contém fábrica de modelos para testes
- `database/migrations` - Contém migrações/criações de tabelas de banco de dados
- `database/seeds` - Contém o semeador de banco de dados
- `resources/view` - Contém views (.blade)
- `resources/js/components` - Contém componentes Vue
- `resources/js/layouts` - Contém layouts/partes externas ao core construídas Vue
- `resources/js/middlewares` - Contém middlewares e verificadores
- `resources/js/pages/auth` - Contém páginas de autenticação construídas Vue
- `resources/js/pages/settings` - Contém páginas de configuração de usuário construídas Vue
- `resources/js/pages/app` - Contém páginas do core do apliactivo construídas Vue
- `resources/js/pages/errors` - Contém páginas de erros construídas Vue
- `resources/js/plugins` - Contém plugins e extenções
- `resources/js/routes` - Contém todos as rotas do SPA
- `routes/js/store` - Contém arquivo de store
- `routes` - Contém todas as rotas api definidas no arquivo api.php

### Variáveis de ambiente

- `.env` - As variáveis ​​de ambiente podem ser definidas neste arquivo