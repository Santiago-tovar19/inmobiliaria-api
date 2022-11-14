<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(BrokersSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ModulesSeeder::class);
        $this->call(ModuleRoleSeeder::class);
        $this->call(PropertyTypesSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(PropertyStatusSeeder::class);
        $this->call(ContractTypesSeeder::class);
        // $this->call(PropertiesSeeder::class);
    }
}
