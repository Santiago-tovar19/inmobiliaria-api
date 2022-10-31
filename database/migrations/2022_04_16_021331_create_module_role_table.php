<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_role', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('module_id')->unsigned()->nullable();
			$table->foreign('module_id')->references('id')->on('modules');
			$table->bigInteger('role_id')->unsigned()->nullable();
			$table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_roles_modulos');
    }
};
