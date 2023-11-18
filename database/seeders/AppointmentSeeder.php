<?php

namespace Database\Seeders;

use App\Models\appointment;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = \Faker\Factory::create('es_ES');

      $propertyIds = Property::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            appointment::create([
                'property_id' => $this->getRandomPropertyId($propertyIds),
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'message' => $faker->text,
                'day_of_week' => $faker->numberBetween(0, 6),
            ]);
        }
    }

     private function getRandomPropertyId($propertyIds)
    {
        return $propertyIds[array_rand($propertyIds)];
    }
}
