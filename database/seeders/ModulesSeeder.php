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
                'name'        => 'Propiedades',
                'description' => 'Gestor de Propiedades',
                'icon'        => 'heroicons_outline:home',
                'path'        => 'propiedades',
                'father_id'   => NULL,
            ],
            [
                'name'        => 'Carros',
                'description' => 'Gestion de carros',
                'icon'        => 'heroicons_outline:home',
                'path'        => 'carros',
                'father_id'   => NULL,
            ]
		]);
    }
}
