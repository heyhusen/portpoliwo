<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'supersu', 'admin', 'user'
        ];
        collect($roles)->each(function ($role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
        });
    }
}
