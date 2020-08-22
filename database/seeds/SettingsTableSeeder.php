<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'site_name',
                'value' => 'My Portfolio'
            ],
            [
                'name' => 'site_description',
                'value' => 'Just another portfolio site'
            ],
            [
                'name' => 'company_name',
                'value' => 'My Company'
            ],
            [
                'name' => 'company_address',
                'value' => 'Jln. Tentara Pelajar, Bandung, Indonesia.'
            ],
            [
                'name' => 'company_phone_number',
                'value' => '(+62) 86242424242'
            ],
            [
                'name' => 'company_email',
                'value' => 'my@company.co'
            ],
        ])->each(function ($item, $key) {
            \App\Models\Setting::firstOrCreate($item);
        });
    }
}
