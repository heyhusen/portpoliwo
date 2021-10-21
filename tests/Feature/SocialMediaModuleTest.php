<?php

namespace Tests\Feature;

use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class SocialMediaModuleTest extends TestCase
{
	use RefreshDatabase;

	public function testCreateSocialMedia()
	{
		SocialMedia::factory()->create([
			'name' => 'Github',
			'icon' => 'github',
			'url' => 'https://github.com/hapakaien'
		]);

		$this->assertDatabaseCount('social_medias', 1)
			->assertDatabaseHas('social_medias', [
				'name' => 'Github',
				'icon' => 'github',
				'url' => 'https://github.com/hapakaien'
			]);
	}

	public function testUpdateSocialMedia()
	{
		SocialMedia::factory()->create();

		$socialMedia = SocialMedia::first();
		$socialMedia->fill([
			'name' => 'Github',
			'icon' => 'github',
			'url' => 'https://github.com/hapakaien'
		]);
		$socialMedia->save();

		$this->assertDatabaseCount('social_medias', 1)
			->assertDatabaseHas('social_medias', [
				'name' => 'Github',
				'icon' => 'github',
				'url' => 'https://github.com/hapakaien'
			]);
	}

	public function testDeleteSocialMedia()
	{
		SocialMedia::factory()->create();

		$socialMedia = SocialMedia::first();
		$socialMedia->delete();

		$this->assertDeleted($socialMedia);
	}

	public function testFailedCreateSocialMediaFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson('/api/social-media');

		$response->assertUnprocessable()
			->assertInvalid(['name', 'icon', 'url'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])],
					'icon' => [trans('validation.required', [
						'attribute' => 'icon'
					])],
					'url' => [trans('validation.required', [
						'attribute' => 'url'
					])]
				]
			]);
	}

	public function testSuccessfullCreateSocialMediaFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson('/api/social-media', [
			'name' => 'Github',
			'icon' => 'github',
			'url' => 'https://github.com/hapakaien'
		]);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => [
					'name' => 'Github',
					'icon' => 'github',
					'url' => 'https://github.com/hapakaien'
				]
			]);

		$response = $this->postJson('/api/social-media', [
			'name' => 'Github',
			'icon' => 'github',
			'url' => 'https://github.com/hapakaien'
		]);

		$this->assertDatabaseCount('social_medias', 1);

		$response->assertUnprocessable()
			->assertInvalid(['name'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.unique', [
						'attribute' => 'name'
					])]
				]
			]);
	}

	public function testFailedReadSocialMediaFromApi()
	{
		(new Auth())->createAuth();

		$uuid = Str::uuid();

		$response = $this->getJson('/api/social-media/' . $uuid);

		$response->assertNotFound()
			->assertJson([
				'success' => false,
				'message' => 'No query results for model [App\\Models\\SocialMedia] ' . $uuid
			]);
	}

	public function testSuccessfullReadSocialMediaFromApi()
	{
		(new Auth())->createAuth();

		SocialMedia::factory()->create([
			'name' => 'Github',
			'icon' => 'github',
			'url' => 'https://github.com/hapakaien'
		]);
		$socialMedia = SocialMedia::first();

		$response = $this->getJson('/api/social-media/' . $socialMedia->id);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'data' => [
					'name' => 'Github',
					'icon' => 'github',
					'url' => 'https://github.com/hapakaien'
				]
			]);
	}

	public function testFailedUpdateSocialMediaFromApi()
	{
		(new Auth())->createAuth();

		SocialMedia::factory()->create();
		$socialMedia = SocialMedia::first();

		$response = $this->putJson('/api/social-media/' . $socialMedia->id, [
			'name' => '',
			'icon' => '',
			'url' => ''
		]);

		$response->assertUnprocessable()
			->assertInvalid(['name', 'icon', 'url'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])],
					'icon' => [trans('validation.required', [
						'attribute' => 'icon'
					])],
					'url' => [trans('validation.required', [
						'attribute' => 'url'
					])],
				]
			]);
	}

	public function testSuccessfullyUpdateSocialMediaFromApi()
	{
		(new Auth())->createAuth();

		SocialMedia::factory()->create();
		$socialMedia = SocialMedia::first();

		$response = $this->putJson('/api/social-media/' . $socialMedia->id, [
			'name' => 'Github',
			'icon' => 'github',
			'url' => 'https://github.com/hapakaien'
		]);

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => [
					'name' => 'Github',
					'icon' => 'github',
					'url' => 'https://github.com/hapakaien'
				]
			]);
	}

	public function testDeleteSocialMediaFromApi()
	{
		(new Auth())->createAuth();

		SocialMedia::factory()->create();
		$socialMedia = SocialMedia::first();

		$response = $this->deleteJson('/api/social-media/', [
			'selectedData' => [$socialMedia->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);
		$this->assertDeleted($socialMedia);
	}
}
