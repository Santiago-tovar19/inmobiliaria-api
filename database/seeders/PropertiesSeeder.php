<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('properties')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create('es_ES');
        $propertyTypes = DB::table('property_types')->get()->count();
        $currencies = DB::table('currencies')->get()->count();
        $propertyStatus = DB::table('property_status')->get()->count();

        for ($i=0; $i < 100; $i++) {
            $properties[] = [
                'name'                    => $faker->sentence(3),
                'description'             => $faker->text(600),
                'property_type_id'        => $faker->numberBetween(1, $propertyTypes),
                'address'                 => $faker->address(),
                'mls_number'              => $faker->phoneNumber(),
                'location_type'           => $faker->randomElement(['Provincia', 'Ciudad']),
                'bedrooms'                => $faker->numberBetween(1, 5),
                'construction_year'       => $faker->year(),
                'bathrooms'               => $faker->numberBetween(1, 5),
                'size'                    => $faker->numberBetween(50, 500),
                'price'                   => $faker->numberBetween(1000, 1000),
                'currency_id'             => $faker->numberBetween(1, $currencies),
                'youtube_link'            => 'https://www.youtube.com/embed/LXb3EKWsInQ',
                'status_id'               => $faker->numberBetween(1, $propertyStatus),
                'parking'                 => $faker->boolean(),
                'hoa'                     => $faker->boolean(),
                'stories'                 => $faker->boolean(),
                'exclusions'              => $faker->boolean(),
                'level'                   => $faker->boolean(),
                'security'                => $faker->boolean(),
                'lobby'                   => $faker->boolean(),
                'balcony'                 => $faker->boolean(),
                'terrace'                 => $faker->boolean(),
                'power_plant'             => $faker->boolean(),
                'gym'                     => $faker->boolean(),
                'walk_in_closet'          => $faker->boolean(),
                'swimming_pool'           => $faker->boolean(),
                'kids_area'               => $faker->boolean(),
                'pets_allowed'            => $faker->boolean(),
                'central_air_conditioner' => $faker->boolean(),
                'published_at'            => now()
            ];
        }

        // print_r($properties);
        DB::table('properties')->insert($properties);

        $properties = DB::table('properties')->get();

        foreach ($properties as $property) {
            $images = [];
            for ($i=0; $i < 5; $i++) {
                $images[] = [
                    'property_id' => $property->id,
                    'path' => $faker->imageUrl(1980, 1280),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('property_images')->insert($images);
        }
    }
}
