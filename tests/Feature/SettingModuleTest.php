<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Auth;
use Tests\TestCase;

class SettingModuleTest extends TestCase
{
	use RefreshDatabase;

	protected $table = 'settings';
	protected $url = '/api/setting';

	public function testCreateSetting()
	{
		Setting::factory()->create([
			'name' => 'site_name',
			'value' => 'My Portfolio'
		]);

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, [
				'name' => 'site_name',
				'value' => 'My Portfolio'
			]);
	}


	public function testUpdateSetting()
	{
		Setting::factory()->create();

		$setting = Setting::first();
		$setting->fill([
			'name' => 'site_name',
			'value' => 'My Portfolio'
		]);
		$setting->save();

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, [
				'name' => 'site_name',
				'value' => 'My Portfolio'
			]);
	}

	public function testDeleteSetting()
	{
		Setting::factory()->create();

		$setting = Setting::first();
		$setting->delete();

		$this->assertDeleted($setting);
	}

	public function testFailedSaveSettingFromApi()
	{
		$auth = new Auth();
		$auth->createAuth();

		$response = $this->postJson($this->url);

		$response->assertUnprocessable()
			->assertInvalid([
				'site_name',
				'site_description',
				'company_name',
				'company_address',
				'company_phone_number',
				'company_email',
			])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'site_name' => [trans('validation.required', [
						'attribute' => 'site name'
					])],
					'site_description' => [trans('validation.required', [
						'attribute' => 'site description'
					])],
					'company_name' => [trans('validation.required', [
						'attribute' => 'company name'
					])],
					'company_address' => [trans('validation.required', [
						'attribute' => 'company address'
					])],
					'company_phone_number' => [trans('validation.required', [
						'attribute' => 'company phone number'
					])],
					'company_email' => [trans('validation.required', [
						'attribute' => 'company email'
					])]
				]
			]);
	}

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

		$response->assertUnprocessable()
			->assertInvalid(['company_email'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'company_email' => [trans('validation.email', [
						'attribute' => 'company email'
					])]
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

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
			]);
	}
}
