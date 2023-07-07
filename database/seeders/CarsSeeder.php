<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('cars')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create('es_ES');
        $propertyTypes = DB::table('property_types')->get()->count();

        for ($i=0; $i < 100; $i++) {
            $cars[] = [
                "year" => $faker->year(),
                "color" => $faker->colorName(),
                "doors" => $faker->numberBetween(2, 5),
                "brand" => $faker->company(),
                "model" => $faker->company(),
                "placa" => $faker->postcode(),
                "owner_name" => $faker->name(),
            ];
        }

        DB::table('cars')->insert($cars);
    }
}
