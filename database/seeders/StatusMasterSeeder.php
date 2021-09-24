<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_master')->truncate();
        DB::table('status_master')->insert([
            ['id' => 1, 'status' => 1, 'name' => 'Active', 'description' => 'Active'],
            ['id' => 2, 'status' => 0, 'name' => 'Inactive', 'description' => 'Inactive'],
            ['id' => 3, 'status' => 2, 'name' => 'Account Blocked', 'description' => 'Account Blocked By ONEX admin'],
            ['id' => 4, 'status' => 3, 'name' => 'Deleted', 'description' => 'Deleted']
        ]);
    }
}
