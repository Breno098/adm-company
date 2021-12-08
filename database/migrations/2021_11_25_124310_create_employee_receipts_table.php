<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_receipts', function (Blueprint $table) {
            $table->id();
            $table->date('date_start')->nullable();
            $table->time('time_start')->nullable();
            $table->date('date_end')->nullable();
            $table->time('time_end')->nullable();
            $table->float('amount', 15, 2)->nullable();
            $table->float('discount_amount', 15, 2)->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained();
            $table->foreignId('company_id')->nullable()->constrained();
            $table->boolean('paid')->default(true);
            $table->string('path')->nullable();
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
        Schema::dropIfExists('employee_receipts');
    }
}
