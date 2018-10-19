<?php

namespace App\Console\Commands;

use App\Role;
use Illuminate\Console\Command;

class InsertRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert a new role';

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
     * @return mixed
     */
    public function handle()
    {
        $role = new Role();
        $role->name = 'role7';
        $role->save();
    }
}
