<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateUserTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fake users to testing purpose';

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
        $userRows = 10;
        if ($this->option('user') != '') {
            $userRows = $this->option('user');
        }
        \App\Models\User::factory()
            ->has(\App\Models\BusinessAccount::factory())
            ->count($userRows)
            ->create();
        
        echo  $userRows . " - Users has been created successfully";
    }
}
