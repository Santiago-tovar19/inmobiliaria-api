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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('document')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
			$table->foreign('country_id')->references('id')->on('countries');
            $table->bigInteger('role_id')->unsigned()->nullable();
			$table->foreign('role_id')->references('id')->on('roles');
            $table->string('sex')->nullable();
            $table->string('img')->nullable();
            $table->string('born_at')->nullable();
            $table->integer('active')->default(1);
            $table->integer('deleted')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
