<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supersu = User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => 'supersu@example.com',
            'password' => Hash::make('HowDoIKnow?!'),
        ]);
        $supersu->assignRole('supersu');

        $admin = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('HowDoIKnow?!'),
        ]);
        $admin->assignRole('admin');

        $user = User::firstOrCreate([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('HowDoIKnow?!'),
        ]);
        $user->assignRole('user');
    }
}
