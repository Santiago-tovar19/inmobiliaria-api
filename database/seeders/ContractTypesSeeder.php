<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTypesSeeder extends Seeder
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
	    DB::table('contract_types')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('contract_types')->insert([

            // Sistema v2
            [
                'name' => 'Venta',
            ],
            [
                'name' => 'Alquiler',
            ]
	    ]);
    }
}
