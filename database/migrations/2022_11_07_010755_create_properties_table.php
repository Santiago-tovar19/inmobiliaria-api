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
            $table->string('video')->nullable();; // Video

            $table->string('size')->nullable();; // Tamaño (metros)
            $table->string('price')->nullable();; // Precio
            $table->bigInteger('currency_id')->unsigned()->nullable();
			$table->foreign('currency_id')->references('id')->on('currencies'); // Tipo de Moneda (USD / DOP(RD$))
            $table->string('youtube_link')->nullable(); // Vídeo (YouTube Link)
            $table->bigInteger('status_id')->unsigned()->nullable();
			$table->foreign('status_id')->references('id')->on('property_status'); // Estado de la propiedad (Activa, Pendiente, Cerrado)

            $table->bigInteger('contract_type_id')->unsigned()->nullable();
			$table->foreign('contract_type_id')->references('id')->on('contract_types'); // Tipo de contrato (Venta, Alquiler)

            $table->string('lat')->nullable(); // Estacionamiento
            $table->string('lon')->nullable(); // Cocina

            $table->integer('parking')->default(0); // Estacionamiento
            $table->integer('kitchen')->default(0); // Cocina
            $table->integer('elevator')->default(0); // Elevador
            $table->integer('wifi')->default(0); // Wifi
            $table->integer('fireplace')->default(0); // Chimenea
            $table->integer('hoa')->default(0); // HOA
            $table->integer('stories')->default(0); // Cuentos
            $table->integer('exclusions')->default(0); // Exclusiones
            $table->integer('level')->default(0); // Piso (Apartamento)
            $table->integer('security')->default(0); // Seguridad
            $table->integer('lobby')->default(0); // Vestíbulo
            $table->integer('balcony')->default(0); // Balcon
            $table->integer('terrace')->default(0); // Terraza
            $table->integer('power_plant')->default(0); // Planta electrica
            $table->integer('gym')->default(0); // Gimnasio
            $table->integer('walk_in_closet')->default(0); // Vestidor
            $table->integer('swimming_pool')->default(0); // Piscina
            $table->integer('kids_area')->default(0); // Area de niños
            $table->integer('pets_allowed')->default(0); // Mascotas permitidas
            $table->integer('central_air_conditioner')->default(0); // Aire Acondicionado central
            $table->integer('published')->default(0); // Aire Acondicionado central
            $table->integer('featured')->default(0); // Destacado

            $table->bigInteger('created_by')->unsigned()->nullable();
			$table->foreign('created_by')->references('id')->on('users'); // Tipo de contrato (Venta, Alquiler)
            $table->bigInteger('broker_id')->unsigned()->nullable();
            $table->foreign('broker_id')->references('id')->on('brokers');

            $table->string('published_at')->nullable(); // Fecha Publicada
            $table->softDeletes();
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
