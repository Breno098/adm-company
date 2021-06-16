<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->dateTime('date')->nullable();
            $table->dateTime('validity')->nullable();
            $table->boolean('active')->default(true);
            $table->float('value')->nullable();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->foreignId('type_id')->nullable()->constrained('payment_types');
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
        Schema::dropIfExists('payments');
    }
}
