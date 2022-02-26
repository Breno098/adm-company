<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('complaint')->nullable();
            $table->text('comments')->nullable();
            $table->dateTime('execution_date')->nullable();
            $table->date('technical_visit_date')->nullable();
            $table->time('technical_visit_time')->nullable();
            $table->float('amount')->nullable();
            $table->float('amount_paid')->nullable();
            $table->integer('number_of_installments')->nullable();
            $table->float('discount_amount')->nullable();
            $table->float('discount_percentage')->nullable();
            $table->integer('warranty_days')->nullable();
            $table->text('warranty_conditions')->nullable();
            $table->enum('type', ['budget', 'expense'])->nullable();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('status_id')->nullable()->constrained();
            $table->foreignId('address_id')->nullable()->constrained();
            $table->text('work_found')->nullable();
            $table->text('work_done')->nullable();
            $table->enum('status', ['CONCLUÍDO', 'EM ANDAMENTO', 'CANCELADO', 'AGUARDANDO APROVAÇÃO', 'AGUARDANDO PAGAMENTO'])->nullable();
            $table->enum('payment_status', ['PAGO TOTAL', 'PAGO PARCIAL', 'EM ABERTO', 'CANCELADO', 'INADIMPLENTE'])->nullable();
            $table->string('accepted_payment_methods')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
