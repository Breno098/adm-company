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
            $table->boolean('active')->default(true);
            $table->enum('type', ['budget', 'sale', 'service', 'expense']);
            $table->dateTime('execution_date')->nullable();
            $table->float('total_value')->nullable();
            $table->float('discount_amount')->nullable();
            $table->float('discount_percentage')->nullable();
            $table->integer('warranty_days')->nullable();
            $table->text('warranty_conditions')->nullable();

            $table->foreignId('previous_step_id')->nullable()->constrained('orders');
            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('status_id')->nullable()->constrained();
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
