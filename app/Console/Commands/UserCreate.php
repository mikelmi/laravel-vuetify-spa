<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
        {email : Email}
        {--N|name= : Name}
        {--P|password= : Password}
        {--A|admin : Admin permission}';

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
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');

        $name = $this->option('name');

        if (!$name) {
            $name = $this->ask('Name');
        }

        if (!$name) {
            $this->error('Name can not be empty');

            return false;
        }

        $password = $this->option('password');

        if (!$password) {
            $password = $this->secret('Password');
        }

        if (!$password) {
            $this->error('Password can not be empty');
            return false;
        }

        $passwordConfirmation = $this->secret('Confirm Password');

        if ($password !== $passwordConfirmation) {
            $this->error('Passwords do not match');
            return false;
        }

        $isAdmin = $this->option('admin');

        if (empty($isAdmin)) {
            $isAdmin = $this->confirm('Add admin privileges');
        }

        $data = compact('email');

        $validator = Validator::make($data, [
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }

        $user = new User();
        $user->email = $this->argument('email');
        $user->password = Hash::make($password);
        $user->name = $name;
        $user->is_admin = $isAdmin;

        $user->save();

        $this->info('User Created');
    }
}
