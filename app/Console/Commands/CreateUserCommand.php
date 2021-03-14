<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserCommand extends Command
{
    protected $signature = 'users:create {name?} {email?}';
    protected $description = 'Create a new user';

    public function handle()
    {
        $name = $this->argument('name') ?: $this->ask('Name');
        $email = $this->argument('email') ?: $this->ask('E-Mail');
        $plainPassword = Str::random(32);

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($plainPassword);
        $user->remember_token = '';
        $user->is_admin = true;

        $user->saveOrFail();

        $this->line('User created successfully,');
        $this->line('Default password: ' . $plainPassword);
    }
}
