<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payment', function (Blueprint $table) {
            $table->id();
            $table->float('value')->nullable();
            $table->dateTime('date')->nullable();
            $table->dateTime('validity')->nullable();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('payment_id')->nullable()->constrained();
            $table->integer('times')->default(1);
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
        Schema::dropIfExists('order_payment');
    }
}
