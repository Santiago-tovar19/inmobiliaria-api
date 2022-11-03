<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UsersSeeder extends Seeder
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
				DB::table('users')->truncate();
				DB::statement('SET FOREIGN_KEY_CHECKS=1;');

				$users = [
					[
						'full_name'         => 'José Andrés',
						'email'             => 'andresjosehr@gmail.com',
						'username'          => 'andresjosehr',
						'password'          => 'Paralelepipe2',
						'email_verified_at' => now(),
						'role_id'           => DB::table('roles')->where('name', 'Master Admin')->first()->id,
					],
                    [
						'full_name'         => 'Admin Master',
						'email'             => 'adminmaster@gmail.com',
						'username'          => 'adminmaster',
						'password'          => 'adminmaster',
						'email_verified_at' => now(),
						'role_id'           => DB::table('roles')->where('name', 'Master Admin')->first()->id,
					],
                    [
						'full_name'         => 'Admin',
						'email'             => 'admin@gmail.com',
						'username'          => 'admin',
						'password'          => 'admin',
						'email_verified_at' => now(),
						'role_id'           => DB::table('roles')->where('name', 'Admin')->first()->id,
					],
                    [
						'full_name'         => 'Agente',
						'email'             => 'agente@gmail.com',
						'username'          => 'agente',
						'password'          => 'agente',
						'email_verified_at' => now(),
						'role_id'           => DB::table('roles')->where('name', 'Agente')->first()->id,
					],
                    [
						'full_name'         => 'Consumidor',
						'email'             => 'consumidor@gmail.com',
						'username'          => 'consumidor',
						'password'          => 'consumidor',
						'email_verified_at' => now(),
						'role_id'           => DB::table('roles')->where('name', 'Consumidor')->first()->id,
					],
				];


				foreach ($users as $user) {
					if(!DB::table('users')->where('email', $user['email'])->first()) {
						DB::table('users')->insert([
							'full_name' => $user['full_name'],
							'email' => $user['email'],
                            'username' => $user['username'],
							'password' => bcrypt($user['password']),
                            'email_verified_at' => $user['email_verified_at'],
                            'role_id' => $user['role_id'],
						]);
					}
				}


    }

		public function getRoleID(String $name)
		{
			return DB::table('roles')->where('name', $name)->first()->id;
		}
}
