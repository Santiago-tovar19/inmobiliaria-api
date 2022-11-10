<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	    DB::table('property_status')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('property_status')->insert([

            // Sistema v2
            [
                'name' => 'Activa',
            ],
            [
                'name' => 'Pendiente',
            ],
            [
                'name' => 'Cerrado',
            ]
	    ]);
    }
}
