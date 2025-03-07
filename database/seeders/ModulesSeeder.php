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
        // Truncate all tablesfono
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('modules')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('modules')->insert([
            [
                'name'        => 'Usuarios',
                'description' => 'Gestor de Usuarios',
                'icon'        => 'heroicons_outline:user-group',
                'path'        => 'usuarios',
                'father_id'   => NULL,
            ],
            [
                'name'        => 'Adm. de Propiedades',
                'description' => 'Gestor de Propiedades',
                'icon'        => 'heroicons_outline:home',
                'path'        => 'properties',
                'father_id'   => NULL,
            ],
            [
                'name'        => 'Reportes',
                'description' => 'Gestor de Reportes',
                'icon'        => 'heroicons_outline:chart-bar',
                'path'        => 'reportes',
                'father_id'   => NULL,
            ],
            // [
            //     'name'        => 'Carros',
            //     'description' => 'Gestion de carros',
            //     'icon'        => 'heroicons_outline:home',
            //     'path'        => 'carros',
            //     'father_id'   => NULL,
            // ]
		]);
    }
}
