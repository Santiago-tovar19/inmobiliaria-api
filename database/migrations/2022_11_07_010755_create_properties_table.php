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
            $table->string('name')->nullable();; // Descripción (600 caracteres máx.)
            $table->longText('description')->nullable();; // Descripción (600 caracteres máx.)
            $table->bigInteger('property_type_id')->unsigned()->nullable();
			$table->foreign('property_type_id')->references('id')->on('property_types'); // Tipo de Propiedad (Casa, Apartamento, Penthouse, Nuevo Proyecto, Terreno, Corporativo, Propiedad Vacacional)
            $table->string('address')->nullable();; // Dirección
            $table->string('mls_number')->nullable();; // Número MLS
            $table->string('construction_year')->nullable();; // Año de Construcción
            $table->string('location_type')->nullable();; // Tipo de localizacion (Provincia o Ciudad)
            $table->string('bedrooms')->nullable();; // Dormitorios
            $table->string('bathrooms')->nullable();; // Baños

            $table->string('size')->nullable();; // Tamaño (metros)
            $table->string('price')->nullable();; // Precio
            $table->bigInteger('currency_id')->unsigned()->nullable();
			$table->foreign('currency_id')->references('id')->on('currencies'); // Tipo de Moneda (USD / DOP(RD$))
            $table->string('youtube_link')->nullable(); // Vídeo (YouTube Link)
            $table->bigInteger('status_id')->unsigned()->nullable();
			$table->foreign('status_id')->references('id')->on('property_status'); // Estado de la propiedad (Activa, Pendiente, Cerrado)

            $table->bigInteger('contract_type_id')->unsigned()->nullable();
			$table->foreign('contract_type_id')->references('id')->on('contract_types'); // Tipo de contrato (Venta, Alquiler)

            $table->boolean('parking')->default(false); // Estacionamiento
            $table->boolean('kitchen')->default(false); // Cocina
            $table->boolean('elevator')->default(false); // Elevador
            $table->boolean('wifi')->default(false); // Wifi
            $table->boolean('fireplace')->default(false); // Chimenea
            $table->boolean('hoa')->default(false); // HOA
            $table->boolean('stories')->default(false); // Cuentos
            $table->boolean('exclusions')->default(false); // Exclusiones
            $table->boolean('level')->default(false); // Piso (Apartamento)
            $table->boolean('security')->default(false); // Seguridad
            $table->boolean('lobby')->default(false); // Vestíbulo
            $table->boolean('balcony')->default(false); // Balcon
            $table->boolean('terrace')->default(false); // Terraza
            $table->boolean('power_plant')->default(false); // Planta electrica
            $table->boolean('gym')->default(false); // Gimnasio
            $table->boolean('walk_in_closet')->default(false); // Vestidor
            $table->boolean('swimming_pool')->default(false); // Piscina
            $table->boolean('kids_area')->default(false); // Area de niños
            $table->boolean('pets_allowed')->default(false); // Mascotas permitidas
            $table->boolean('central_air_conditioner')->default(false); // Aire Acondicionado central

            $table->bigInteger('created_by')->unsigned()->nullable();
			$table->foreign('created_by')->references('id')->on('users'); // Tipo de contrato (Venta, Alquiler)

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
