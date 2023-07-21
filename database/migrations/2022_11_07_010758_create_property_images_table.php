<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @OA\Schema(
 *     schema="PropertyImage",
 *     title="Property Image",
 *     description="Property Image schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the property image"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the property image"
 *     ),
 *     @OA\Property(
 *         property="property_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID of the property associated with the image"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the image ('Banner' or 'Gallery')"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the property image"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date of the property image"
 *     )
 * )
 */

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('property_id')->unsigned()->nullable();
			$table->foreign('property_id')->references('id')->on('properties');
            $table->string('type'); // 'Banner', 'Gallery'
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
        Schema::dropIfExists('property_images');
    }
};
