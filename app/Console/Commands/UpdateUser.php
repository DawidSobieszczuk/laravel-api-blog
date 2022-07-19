<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Exception;
use Illuminate\Console\Command;

class UpdateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update {id} {--N|name=} {--E|email=} {--P|password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user';

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
            $input = array();
            if ($this->option('name')) $input['name'] = $this->option('name');
            if ($this->option('email')) $input['email'] = $this->option('email');
            if ($this->option('password')) $input['password'] = $this->option('password');

            $userService->updateById(
                $this->argument('id'),
                $input,
            );
            $this->line("<fg=green>User updated.</>");
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $this->line("<fg=white;bg=red>$msg</>");
        }
    }
}
