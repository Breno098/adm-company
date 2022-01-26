<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['CONCLUÍDO', 'EM ANDAMENTO', 'CANCELADO', 'AGUARDANDO APROVAÇÃO', 'AGUARDANDO PAGAMENTO'])->nullable();
            $table->enum('payment_status', ['PAGO TOTAL', 'PAGO PARCIAL', 'EM ABERTO', 'CANCELADO', 'INADIMPLENTE'])->nullable();
            $table->string('accepted_payment_methods')->nullable();

            $table->renameColumn('installments', 'number_of_installments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('payment_status');
            $table->dropColumn('accepted_payment_methods');

            $table->renameColumn('number_of_installments', 'installments');
        });
    }
}
