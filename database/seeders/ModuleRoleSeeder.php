<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('module_role')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $modules = Module::where('name', '<>', 'Datos Estudiante')->get();

        DB::table('module_role')->insert([

            // Rol de Master admin
            [
                'module_id' => DB::table('modules')->where('name', 'Usuarios')->first()->id,
                'role_id'   => Role::where('name', 'Master Admin')->first()->id
            ],
            [
                'module_id' => DB::table('modules')->where('name', 'Propiedades')->first()->id,
                'role_id'   => Role::where('name', 'Master Admin')->first()->id
            ],
            [
                'module_id' => DB::table('modules')->where('name', 'Reportes')->first()->id,
                'role_id'   => Role::where('name', 'Master Admin')->first()->id
            ],

            // Rol de Admin
            [
                'module_id' => DB::table('modules')->where('name', 'Propiedades')->first()->id,
                'role_id'   => Role::where('name', 'Admin')->first()->id
            ],
            [
                'module_id' => DB::table('modules')->where('name', 'Usuarios')->first()->id,
                'role_id'   => Role::where('name', 'Admin')->first()->id
            ],
            [
                'module_id' => DB::table('modules')->where('name', 'Reportes')->first()->id,
                'role_id'   => Role::where('name', 'Admin')->first()->id
            ],

            // Rol de agente

            [
                'module_id' => DB::table('modules')->where('name', 'Propiedades')->first()->id,
                'role_id'   => Role::where('name', 'Agente')->first()->id
            ],
            [
                'module_id' => DB::table('modules')->where('name', 'Reportes')->first()->id,
                'role_id'   => Role::where('name', 'Agente')->first()->id
            ],

            // [
            //     'module_id' => DB::table('modules')->where('name', 'Carros')->first()->id,
            //     'role_id'   => Role::where('name', 'Master Admin')->first()->id
            // ],


        ]);
    }
}
