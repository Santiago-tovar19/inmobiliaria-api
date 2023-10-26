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
						'first_name'         => 'José Andrés',
						'email'             => 'andresjosehr@gmail.com',
						'password'          => 'Paralelepipe2',
						'email_verified_at' => now(),
                        'broker_id' => null,
						'role_id'           => DB::table('roles')->where('name', 'Master Admin')->first()->id,
					],
                    [
						'first_name'         => 'Admin Master',
						'email'             => 'adminmaster@gmail.com',
						'password'          => 'adminmaster',
						'email_verified_at' => now(),
                        'broker_id' => null,
						'role_id'           => DB::table('roles')->where('name', 'Master Admin')->first()->id,
					],
                    [
						'first_name'         => 'Admin',
						'email'             => 'admin@gmail.com',
						'password'          => 'admin',
						'email_verified_at' => now(),
                        'broker_id' => 1,
						'role_id'           => DB::table('roles')->where('name', 'Admin')->first()->id,
					],
                    [
						'first_name'         => 'Agente',
						'email'             => 'agente@gmail.com',
						'password'          => 'agente',
						'email_verified_at' => now(),
                        'broker_id' => null,
						'role_id'           => DB::table('roles')->where('name', 'Agente')->first()->id,
					],
                    [
						'first_name'         => 'Consumidor',
						'email'             => 'consumidor@gmail.com',
						'password'          => 'consumidor',
						'email_verified_at' => now(),
                        'broker_id' => null,
						'role_id'           => DB::table('roles')->where('name', 'Consumidor')->first()->id,
					],
				];


				foreach ($users as $user) {
					if(!DB::table('users')->where('email', $user['email'])->first()) {
						DB::table('users')->insert([
							'first_name' => $user['first_name'],
							'email' => $user['email'],
							'password' => bcrypt($user['password']),
                            'email_verified_at' => $user['email_verified_at'],
                            'role_id' => $user['role_id'],
                            'broker_id' => $user['broker_id'],
						]);
					}
				}




    }

		public function getRoleID(String $name)
		{
			return DB::table('roles')->where('name', $name)->first()->id;
		}
}
