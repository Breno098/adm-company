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

            $table->text('description')->nullable();
            $table->text('comments')->nullable();
            
            $table->boolean('active')->default(true);
            $table->dateTime('execution_date')->nullable();

            $table->float('amount')->nullable();
            $table->float('amount_paid')->nullable();

            $table->integer('installments')->nullable();

            $table->float('discount_amount')->nullable();
            $table->float('discount_percentage')->nullable();
            
            $table->integer('warranty_days')->nullable();
            $table->text('warranty_conditions')->nullable();

            $table->enum('type', ['budget', 'expense'])->nullable();

            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('status_id')->nullable()->constrained();
            $table->foreignId('address_id')->nullable()->constrained();

            $table->timestamps();
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
