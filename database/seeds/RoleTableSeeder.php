<?php

use App\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->prefix = 'Admin';
        $role->name = 'Administrador';
        $role->description = 'Administrador del sistema';
        $role->created_by = 'SEED';
        $role->created_at = Carbon::now();
        $role->updated_at = Carbon::now();
        $role->save();
    }
}
