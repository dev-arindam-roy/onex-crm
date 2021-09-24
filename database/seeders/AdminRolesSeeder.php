<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_roles')->truncate();
        DB::table('admin_roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'description' => 'Onex Master - Admin Role have all access'],
            ['id' => 2, 'name' => 'User', 'description' => 'Onex Master - User Role have limited access']
        ]);
    }
}
