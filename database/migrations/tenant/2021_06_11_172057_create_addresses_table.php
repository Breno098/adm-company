<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('street')->nullable();
            $table->string('cep')->nullable();
            $table->string('complement')->nullable();
            $table->string('apartment')->nullable();
            $table->string('floor')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('main')->nullable();
            $table->string('block')->nullable();
            $table->string('house')->nullable();
            $table->string('tower')->nullable();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('employee_id')->nullable()->constrained();
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
        Schema::dropIfExists('addresses');
    }
}
