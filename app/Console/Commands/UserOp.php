<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserOp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:op {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant a user as administrator.';

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new command instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->user->where('email', $email)->update([
            'is_admin' => true
        ]);

        return 0;
    }
}
