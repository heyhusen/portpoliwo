<?php

namespace Database\Seeders;

use App\Models\User;
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
            'email_verified_at' => now(),
            'password' => Hash::make('HowDoIKnow?!'),
        ]);
        $supersu->assignRole('supersu');

        $admin = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('HowDoIKnow?!'),
        ]);
        $admin->assignRole('admin');

        $user = User::firstOrCreate([
            'name' => 'User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('HowDoIKnow?!'),
        ]);
        $user->assignRole('user');
    }
}
