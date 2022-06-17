<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Illuminate\Console\Command;
use Exception;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name} {email} {password} {is_admin=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

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
     * @return void
     */
    public function handle(UserService $userService)
    {
        try {
            $userService->createNewUser(
                $this->argument('name'),
                $this->argument('email'),
                $this->argument('password'),
                boolval($this->argument('is_admin'))
            );
            $this->line("<fg=green>User created.</>");
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $this->line("<fg=white;bg=red>$msg</>");
        }
    }
}
