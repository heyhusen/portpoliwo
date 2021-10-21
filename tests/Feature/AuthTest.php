<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RolesTableSeeder;
use Tests\TestCase;

class AuthTest extends TestCase
{
	use RefreshDatabase;

	public function testUnauthorizedAccess()
	{
		$response = $this->getJson('/api');
		$response->assertUnauthorized()
			->assertJson([
				'success' => false,
				'message' => 'Unauthenticated.'
			]);
	}

	public function testRegisteredUserCanLogInViaToken()
	{
		$this->seed(RolesTableSeeder::class);
		$user = User::factory()->create([
			'name' => 'Ahmad Husen'
		])
			->each(function ($user) {
				$user->assignRole('user');
			});

		$user = User::first();
		$token = $user->createToken('Portpoliwo');

		$response = $this->withHeaders([
			'Authorization' => 'Bearer ' . $token->plainTextToken,
		])
			->getJson('/api');
		$response->assertOk()
			->assertJson([
				'success' => true,
			]);
	}
}
