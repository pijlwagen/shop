<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Michel3951',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email' => 'michel39511@gmail.com'
        ]);
        \App\Models\Role::create([
            'name' => 'Administrator'
        ]);
        \App\Models\UserRole::create([
            'role_id' => 1,
            'user_id' => 1
        ]);
    }
}
