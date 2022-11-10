<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate all tables
	    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	    DB::table('property_types')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('property_types')->insert([
            ['name' => 'Casa'],
            ['name' => 'Apartamento'],
            ['name' => 'Penthouse'],
            ['name' => 'Nuevo Proyecto'],
            ['name' => 'Terreno'],
            ['name' => 'Corporativo'],
            ['name' => 'Propiedad'],
            ['name' => 'Vacacional'],
	    ]);
    }
}
