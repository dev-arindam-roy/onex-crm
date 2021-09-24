<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lastAccount = config('onex.account_start');

        $configs = DB::table('configurations')->get()->toArray();
        if (count($configs)) {
            $lastAccount = $configs[0]->value;
        }

        DB::table('configurations')->truncate();
        DB::table('configurations')->insert([
            array(
                'id' => 1,
                'key' => 'last_account',
                'value' => $lastAccount
            )
        ]);
    }
}
