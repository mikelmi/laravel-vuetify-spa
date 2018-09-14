<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserUpdate extends Command
{
    protected $signature = 'user:update
        {email : Email}
        {--E|email= : New Email}
        {--N|name= : Name}
        {--P|password= : Password}
        {--A|admin : Admin permission}';

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
     * @return mixed
     */
    public function handle()
    {

        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found');
            return false;
        }

        $name = $this->option('name');
        $password = $this->option('password');

        $data = compact('email');

        $validator = Validator::make($data, [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
        ]);

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }

        if ($password) {
            $passwordConfirmation = $this->secret('Confirm Password');

            if ($password !== $passwordConfirmation) {
                $this->error('Passwords do not match');
                return false;
            }

            $user->password = Hash::make($password);
        }

        if ($email) {
            $user->email = $email;
        }

        if ($name) {
            $user->name = $name;
        }

        $user->save();

        $this->info('User Updated');
    }
}
