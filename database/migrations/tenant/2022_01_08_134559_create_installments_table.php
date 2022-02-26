<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('expense_id')->nullable()->constrained();
            $table->enum('payment_method', ['PIX', 'DINHEIRO', 'CARTÃO DÉBITO', 'CARTÃO CRÉDITO', 'CHEQUE', 'BOLETO', 'CONTRATO', 'TRANSFERÊNCIA'])->nullable();
            $table->enum('status', ['PAGO', 'EM ABERTO', 'CANCELADO', 'INADIMPLENTE'])->nullable();
            $table->date('due_date')->nullable();
            $table->date('pay_day')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installments');
    }
}
