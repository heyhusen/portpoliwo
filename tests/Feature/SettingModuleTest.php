<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class SettingModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $table = 'settings';
    protected $url = '/api/setting';

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreateSetting()
    {
        factory(Setting::class)->create([
            'name' => 'site_name',
            'value' => 'My Portfolio'
        ]);

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, [
                'name' => 'site_name',
                'value' => 'My Portfolio'
            ]);
    }

    /**
     * Test updating a record
     *
     * @return void
     */
    public function testUpdateSetting()
    {
        factory(Setting::class)->create();

        $setting = Setting::first();
        $setting->fill([
            'name' => 'site_name',
            'value' => 'My Portfolio'
        ]);
        $setting->save();

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, [
                'name' => 'site_name',
                'value' => 'My Portfolio'
            ]);
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeleteSetting()
    {
        factory(Setting::class)->create();

        $setting = Setting::first();
        $setting->delete();

        $this->assertDeleted($setting);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedSaveSettingFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $response = $this->postJson($this->url);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'site_name' => ['The site name field is required.'],
                    'site_description' => ['The site description field is required.'],
                    'company_name' => ['The company name field is required.'],
                    'company_address' => ['The company address field is required.'],
                    'company_phone_number' => ['The company phone number field is required.'],
                    'company_email' => ['The company email field is required.']
                ]
            ]);
    }

    /**
     * Test creating a record through API
     *
     * @return void
     */
    public function testSuccessfullSaveSettingFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $response = $this->postJson($this->url, [
            'site_name' => 'My Portfolio',
            'site_description' => 'Just another portfolio site',
            'company_name' => 'My Company',
            'company_address' => 'Jln. Tentara Pelajar, Bandung, Indonesia.',
            'company_phone_number' => '(+62) 86242424242',
            'company_email' => 'my'
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'company_email' => ['The company email must be a valid email address.']
                ]
            ]);

        $response = $this->postJson($this->url, [
            'site_name' => 'My Portfolio',
            'site_description' => 'Just another portfolio site',
            'company_name' => 'My Company',
            'company_address' => 'Jln. Tentara Pelajar, Bandung, Indonesia.',
            'company_phone_number' => '(+62) 86242424242',
            'company_email' => 'my@company.co'
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
            ]);
    }
}
