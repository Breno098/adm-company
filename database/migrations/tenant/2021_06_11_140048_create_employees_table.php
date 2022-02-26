<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('position_id')->nullable()->constrained();
            $table->float('salary')->nullable();
            $table->date('admission_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('employees');
    }
}
