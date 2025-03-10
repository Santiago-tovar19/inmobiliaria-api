<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_views', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id')->unsigned()->nullable();
			$table->foreign('property_id')->references('id')->on('properties');
            $table->bigInteger('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users');
            $table->integer('day_of_week')->nullable();
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
        Schema::dropIfExists('property_views');
    }
};
