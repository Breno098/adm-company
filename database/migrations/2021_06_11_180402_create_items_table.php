<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit_measure')->nullable();
            $table->string('brand')->nullable();
            $table->string('bar_code')->nullable();
            $table->enum('type', ['service', 'product'])->nullable();
            $table->float('default_value')->nullable();
            $table->float('cost')->nullable();
            $table->integer('warranty_days')->nullable();
            $table->text('warranty_conditions')->nullable();
            $table->foreignId('status_id')->nullable()->constrained();
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
        Schema::dropIfExists('items');
    }
}
