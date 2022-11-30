<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpgradeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade-user {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrades a given user to moderator status';

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
        /** @var User $user */
        $user = User::where('email', $this->argument('user'))->orWhere('id', $this->argument('user'))->first();

        if ($user == null)
        {
            $this->error("Unable to find a user using supplied '{$this->argument('user')}' argument");
            return Command::FAILURE;
        }

        $user->role = 'moderator';
        $user->save();

        $this->info('The user was successfully upgraded.');

        return Command::SUCCESS;
    }
}
