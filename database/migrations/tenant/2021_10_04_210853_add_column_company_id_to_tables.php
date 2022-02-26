<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCompanyIdToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('images', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign('contacts_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('users', function (Blueprint $table) {
             $table->dropForeign('users_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('items_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign('images_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointments_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign('expenses_company_id_foreign');
            $table->dropColumn('company_id');
        });
    }
}
