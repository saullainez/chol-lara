<?php

use App\SysParam;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SysParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sys_param = new SysParam();
        $sys_param->created_at = Carbon::now();
        $sys_param->updated_at = Carbon::now();
        $sys_param->session_time = 200;
        $sys_param->save();
    }
}
