<?php

namespace Database\Seeders;

use App\Models\FavsUserProperty;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyViewsSeeder extends Seeder
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
        $userIds = User::pluck('id')->toArray();

        // Crear 100 registros de ejemplo
        for ($i = 0; $i < 100; $i++) {
            PropertyView::create([
                'property_id' => $this->getRandomPropertyId($propertyIds),
                'user_id' => $this->getRandomUserId($userIds),
                'day_of_week' => $faker->numberBetween(0, 6),
            ]);
        }
    }

    private function getRandomPropertyId($propertyIds)
    {
        return $propertyIds[array_rand($propertyIds)];
    }

    private function getRandomUserId($userIds)
    {
        return $userIds[array_rand($userIds)];
    }
}
