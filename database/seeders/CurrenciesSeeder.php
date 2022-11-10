<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
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
	    DB::table('currencies')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('currencies')->insert([

            // Sistema v2
            [
                'name' => 'USD',
            ],
            [
                'name' => 'EUR',
            ],
            [
                'name' => 'VES',
            ],
            [
                'name' => 'CLP',
            ]
	    ]);
    }
}
