<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
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
				DB::table('countries')->truncate();
				DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                // Import users from sql\setudents.sql
                $sql = file_get_contents(database_path('seeders/sql/countries.sql'));
                DB::unprepared($sql);
    }
}
