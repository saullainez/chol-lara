<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Saul',
            'email' => 'saullainez@hotmail.es',
            'password' => Hash::make('password'),
            'username' => 'slainez',
            'role_prefix' => 'Admin'
        ]);
            
    }
}
