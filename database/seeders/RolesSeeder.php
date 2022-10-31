<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
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
				DB::table('roles')->truncate();
				DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('roles')->insert([

            // Sistema v2
            [
                'name' => 'Master Admin',
            ],
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'Agente',
            ],
            [
                'name' => 'Consumidor',
            ]
	    ]);
    }
}
