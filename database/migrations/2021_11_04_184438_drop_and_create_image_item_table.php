<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAndCreateImageItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('image_item');

        Schema::create('image_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->nullable()->constrained();
            $table->foreignId('image_id')->nullable()->constrained();
            $table->foreignId('company_id')->nullable()->constrained();
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
        Schema::dropIfExists('image_item');
    }
}
