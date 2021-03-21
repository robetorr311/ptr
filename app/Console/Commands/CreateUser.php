<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
        $this->info('Create a new user:');

        $firstName = $this->ask('First name:');

        $lastName = $this->ask('Last name:');

        $email = $this->ask('Insert email:');

        $password = $this->secret('Insert password:');
        $repeatPassword = $this->secret('Repeat password:');

        while ($password != $repeatPassword) {
            $this->error('Passwords do not match!');

            $password = $this->secret('Insert password:');
            $repeatPassword = $this->secret('Repeat password:');
        }

        $roles = [
            'admin',
            'owner',
            'driver'
        ];

        $role = $this->choice('Choose a role: ', $roles, 1);

        /** @var User $user */
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'name' => $firstName . ' ' .$lastName,
            'verified' => 1,
        ]);

        $user->assignRole($role);

        if (isset($user)) {
            $this->info('User created successfully!');
        } else {
            $this->error('User could not be created!');
        }
    }
}
