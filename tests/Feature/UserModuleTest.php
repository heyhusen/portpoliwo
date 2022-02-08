<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RolesTableSeeder;
use Tests\Feature\Auth;
use Tests\TestCase;

class UserModuleTest extends TestCase
{
	use RefreshDatabase;

	public function testCreateRole()
	{
		$this->seed(RolesTableSeeder::class);
		$this->assertDatabaseCount('roles', 3)
			->assertDatabaseHas('roles', [
				'name' => 'supersu',
				'name' => 'admin',
				'name' => 'user'
			]);
	}

	public function testCreateUser()
	{
		$this->seed(RolesTableSeeder::class);
		(new Auth())->createUser()->each(function ($user) {
			$user->assignRole('user');
		});

		$this->assertDatabaseCount('users', 1)
			->assertDatabaseCount('model_has_roles', 1)
			->assertDatabaseHas('model_has_roles', [
				'model_type' => 'App\Models\User'
			]);
	}

	public function testUpdateUser()
	{
		$this->seed(RolesTableSeeder::class);
		(new Auth())->createUser()->each(function ($user) {
			$user->assignRole('user');
		});

		$user = User::first();
		$user->fill([
			'name' => 'Ahmad Husen',
		]);
		$user->save();

		$this->assertDatabaseCount('users', 1)
			->assertDatabaseHas('users', [
				'name' => 'Ahmad Husen'
			])->assertDatabaseCount('model_has_roles', 1)
			->assertDatabaseHas('model_has_roles', [
				'model_type' => 'App\Models\User'
			]);
	}

	public function testDeleteUser()
	{
		$this->seed(RolesTableSeeder::class);
		(new Auth(['name' => 'Ahmad Husen']))->createUser()
			->each(function ($user) {
				$user->assignRole('user');
			});

		$user = User::first();
		$user->delete();

		$this->assertDeleted($user);
	}

	public function testFailedCreateUserFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson('/api/account');

		$response->assertUnprocessable()
			->assertInvalid(['name', 'email', 'password', 'password_repeat'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])],
					'email' => [trans('validation.required', [
						'attribute' => 'email'
					])],
					'password' => [trans('validation.required', [
						'attribute' => 'password'
					])],
					'password_repeat' => [trans('validation.required', [
						'attribute' => 'password repeat'
					])]
				]
			]);
	}

	public function testSuccessfullCreateUserFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson('/api/account', [
			'name' => 'Ahmad Husen',
			'email' => 'husen@portpoliwo.app',
			'password' => 'EverybodyKnows',
			'password_repeat' => 'EverybodyKnows'
		]);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => [
					'name' => 'Ahmad Husen',
					'email' => 'husen@portpoliwo.app'
				]
			]);
		// Check default avatar (gravatar)
		$this->assertStringContainsString('gravatar', $response['data']['avatar']);
	}

	public function testSuccessfullCreateUserWithAvatarFromApi()
	{
		(new Auth())->createAuth();

		Storage::fake('local');

		$avatar = UploadedFile::fake()->image('avatar.jpg');

		$response = $this->postJson('/api/account', [
			'name' => 'Ahmad Husen',
			'email' => 'husen@portpoliwo.app',
			'password' => 'EverybodyKnows',
			'password_repeat' => 'EverybodyKnows',
			'photo' => $avatar
		]);

		Storage::assertExists('/public/avatar/' . $response['data']['photo']);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => [
					'name' => 'Ahmad Husen',
					'email' => 'husen@portpoliwo.app'
				]
			]);
		// Check custom avatar
		$this->assertStringNotContainsString('gravatar', $response['data']['avatar']);
	}

	public function testFailedReadUserFromApi()
	{
		(new Auth())->createAuth();

		$uuid = Str::uuid();

		$response = $this->getJson('/api/account/' . $uuid);

		$response->assertNotFound()
			->assertJson([
				'success' => false,
				'message' => 'No query results for model [App\\Models\\User] ' . $uuid
			]);
	}

	public function testSuccessfullReadUserFromApi()
	{
		(new Auth([
			'name' => 'Ahmad Husen',
			'email' => 'husen@portpoliwo.app'
		]))->createAuth();

		$user = User::first();

		$response = $this->getJson('/api/account/' . $user->id);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'data' => [
					'name' => 'Ahmad Husen',
					'email' => 'husen@portpoliwo.app'
				]
			]);
		// Check default avatar (gravatar)
		$this->assertStringContainsString('gravatar', $response['data']['avatar']);
	}

	public function testFailedUpdateUserFromApi()
	{
		(new Auth())->createAuth();

		$user = User::first();

		$response = $this->putJson('/api/account/' . $user->id, [
			'name' => '',
			'email' => '',
			'password' => '',
			'password_repeat' => ''
		]);

		$response->assertUnprocessable()
			->assertInvalid(['name', 'email', 'password', 'password_repeat'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])],
					'email' => [trans('validation.required', [
						'attribute' => 'email'
					])],
					'password' => [trans('validation.min.string', [
						'attribute' => 'password',
						'min' => 8
					])],
					'password_repeat' => [trans('validation.min.string', [
						'attribute' => 'password repeat',
						'min' => 8
					])]
				]
			]);
	}

	public function testSuccessfullyUpdateUserFromApi()
	{
		(new Auth())->createAuth();

		$user = User::first();

		$response = $this->putJson('/api/account/' . $user->id, [
			'name' => 'Ahmad Husen',
			'email' => 'husen@portpoliwo.app'
		]);

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => [
					'name' => 'Ahmad Husen',
					'email' => 'husen@portpoliwo.app'
				]
			]);
	}

	public function testSuccessfullyUpdateUserWithAvatarFromApi()
	{
		(new Auth())->createAuth();

		$user = User::first();

		Storage::fake('local');

		$avatar = UploadedFile::fake()->image('avatar.jpg');

		$response = $this->putJson('/api/account/' . $user->id, [
			'name' => 'Ahmad Husen',
			'email' => 'husen@portpoliwo.app',
			'photo' => $avatar
		]);

		Storage::assertExists('/public/avatar/' . $response['data']['photo']);

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => [
					'name' => 'Ahmad Husen',
					'email' => 'husen@portpoliwo.app'
				]
			]);

		// Check custom avatar
		$this->assertStringNotContainsString('gravatar', $response['data']['avatar']);
	}

	public function testDeleteUserFromApi()
	{
		$auth = new Auth();
		$auth->createAuth();
		$user = $auth->createUser();

		$response = $this->deleteJson('/api/account/', [
			'selectedData' => [$user->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertDeleted($user);
	}

	public function testDeleteCurrentLoggedInUserFromApi()
	{
		(new Auth([
			'name' => 'Ahmad Husen'
		]))->createAuth();

		$user = User::first();

		$response = $this->deleteJson('/api/account/', [
			'selectedData' => [$user->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertDatabaseCount('users', 1)
			->assertDatabaseHas('users', [
				'name' => 'Ahmad Husen'
			]);
	}
}
