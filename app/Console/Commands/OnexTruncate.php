<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class OnexTruncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onex:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('users')->truncate();
        DB::table('business_accounts')->truncate();
        DB::table('configurations')->where('key', 'last_account')->update(['value' => config('onex.account_start')]);

        echo "Onex tables has been reset successfully";
    }
}
