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
        Schema::create('favs_user_properties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id')->unsigned()->nullable();
			$table->foreign('property_id')->references('id')->on('properties');
            $table->bigInteger('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('favs_user_properties');
    }
};
