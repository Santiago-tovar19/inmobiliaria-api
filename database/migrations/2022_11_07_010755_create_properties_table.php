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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Descripción (600 caracteres máx.)
            $table->longText('description'); // Descripción (600 caracteres máx.)
            $table->bigInteger('property_type_id')->unsigned()->nullable();
			$table->foreign('property_type_id')->references('id')->on('property_types'); // Tipo de Propiedad (Casa, Apartamento, Penthouse, Nuevo Proyecto, Terreno, Corporativo, Propiedad Vacacional)
            $table->string('address'); // Dirección
            $table->string('mls_number'); // Número MLS
            $table->string('construction_year'); // Año de Construcción
            $table->string('location_type'); // Tipo de localizacion (Provincia o Ciudad)
            $table->string('bedrooms'); // Dormitorios
            $table->string('bathrooms'); // Baños

            $table->string('size'); // Tamaño (metros)
            $table->string('price'); // Precio
            $table->bigInteger('currency_id')->unsigned()->nullable();
			$table->foreign('currency_id')->references('id')->on('currencies'); // Tipo de Moneda (USD / DOP(RD$))
            $table->string('youtube_link'); // Vídeo (YouTube Link)
            $table->bigInteger('status_id')->unsigned()->nullable();
			$table->foreign('status_id')->references('id')->on('property_status'); // Estado de la propiedad (Activa, Pendiente, Cerrado)

            $table->boolean('parking')->default(false);
            $table->boolean('hoa')->default(false);
            $table->boolean('stories')->default(false);
            $table->boolean('exclusions')->default(false);
            $table->boolean('level')->default(false);
            $table->boolean('security')->default(false);
            $table->boolean('lobby')->default(false);
            $table->boolean('balcony')->default(false);
            $table->boolean('terrace')->default(false);
            $table->boolean('power_plant')->default(false);
            $table->boolean('gym')->default(false);
            $table->boolean('walk_in_closet')->default(false);
            $table->boolean('swimming_pool')->default(false);
            $table->boolean('kids_area')->default(false);
            $table->boolean('pets_allowed')->default(false);
            $table->boolean('central_air_conditioner')->default(false);

            $table->string('published_at')->nullable(); // Fecha Publicada
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
        Schema::dropIfExists('properties');
    }
};
