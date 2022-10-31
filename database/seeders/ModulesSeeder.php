<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
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
		DB::table('modules')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('modules')->insert([
            [
                'name'        => 'Master Admin',
                'description' => 'Master Admin',
                'icon'        => 'heroicons_outline:adjustments-vertical',
                'path'        => 'usuarios',
                'father_id'   => NULL,
            ],
            [
                'name'        => 'Admin',
                'description' => 'Admin',
                'icon'        => 'heroicons_outline:adjustments-vertical',
                'path'        => 'usuarios',
                'father_id'   => NULL,
            ],
            [
                'name'        => 'Agente',
                'description' => 'Agente',
                'icon'        => 'heroicons_outline:adjustments-vertical',
                'path'        => 'usuarios',
                'father_id'   => NULL,
            ],
            [
                'name'        => 'Consumidor',
                'description' => 'Consumidor',
                'icon'        => 'heroicons_outline:adjustments-vertical',
                'path'        => 'usuarios',
                'father_id'   => NULL,
            ],



            [
                'name'        => 'Usuarios',
                'description' => 'Gestor de Usuarios',
                'icon'        => 'heroicons_outline:user-group',
                'path'        => 'usuarios',
                'father_id'   => NULL,
            ],
            [
                'name'        => 'Propiedades',
                'description' => 'Gestor de Propiedades',
                'icon'        => 'heroicons_outline:user-group',
                'path'        => 'propiedades',
                'father_id'   => NULL,
            ],
		]);
    }
}
