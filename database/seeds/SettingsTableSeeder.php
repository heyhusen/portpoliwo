<?php

use App\Models\Setting;
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
                'where' => [
                    'slug' => 'site_name',
                ],
                'data' => [
                    'name' => 'Site name',
                    'value' => 'My Portfolio'
                ]
            ],
            [
                'where' => [
                    'slug' => 'site_description',
                ],
                'data' => [
                    'name' => 'Site description',
                    'value' => 'Just another portfolio site'
                ]
            ],
            [
                'where' => [
                    'slug' => 'company_name',
                ],
                'data' => [
                    'name' => 'Company Name',
                    'value' => 'My Company'
                ]
            ],
            [
                'where' => [
                    'slug' => 'company_address',
                ],
                'data' => [
                    'name' => 'Company Address',
                    'value' => 'Jln. Tentara Pelajar, Bandung, Indonesia.'
                ]
            ],
            [
                'where' => [
                    'slug' => 'company_phone_number',
                ],
                'data' => [
                    'name' => 'Company Phone Number',
                    'value' => '(+62) 86242424242'
                ]
            ],
            [
                'where' => [
                    'slug' => 'company_email',
                ],
                'data' => [
                    'name' => 'Company E-Mail',
                    'value' => 'my@company.co'
                ]
            ],
        ])->each(function ($item, $key) {
            Setting::updateOrCreate($item['where'], $item['data']);
        });
    }
}
