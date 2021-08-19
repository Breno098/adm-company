<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_title_option')->nullable();
            $table->string('second_title_option')->nullable();
            $table->string('third_title_option')->nullable();
            $table->string('fourth_title_option')->nullable();
            $table->string('fifth_title_option')->nullable();
            $table->string('route_name')->nullable();
            $table->string('params')->nullable();
            $table->string('link')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->enum('type', ['menu', 'app'])->nullable();
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
        Schema::dropIfExists('application_preferences');
    }
}
