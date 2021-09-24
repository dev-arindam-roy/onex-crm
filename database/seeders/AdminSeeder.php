<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mailIds = [
            'onexmaster.superadmin@onexcrm.com',
            'arindam.roy.master@onexcrm.com'
        ];
        DB::table('admins')->whereIn('email_id', $mailIds)->delete();
        DB::table('admins')->insert([
            [
                'role_id' => 1,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email_id' => 'onexmaster.superadmin@onexcrm.com',
                'password' => Hash::make('B@ck$pace!1987'),
                'status' => 1,
                'email_verified_at' => now()
            ],
            [
                'role_id' => 1,
                'first_name' => 'Arindam',
                'last_name' => 'Roy',
                'email_id' => 'arindam.roy.master@onexcrm.com',
                'password' => Hash::make('B@ck$pace!1987'),
                'status' => 1,
                'email_verified_at' => now()
            ]
        ]);
    }
}
