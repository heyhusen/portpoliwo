<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use RolesTableSeeder;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthorized access
     *
     * @return void
     */
    public function testUnauthorizedAccess()
    {
        $response = $this->getJson('/api');

        $response
            ->assertUnauthorized()
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.'
            ]);
    }

    /**
     * Test if registered user can log in
     * This test is use Sanctum
     *
     * @return void
     */
    public function testRegisteredUserCanLogInViaToken()
    {
        $this->seed(RolesTableSeeder::class);
        $user = factory(User::class)
           ->create([
               'name' => 'Ahmad Husen'
           ])
           ->each(function ($user) {
                $user->assignRole('user');
            });

        $user = User::first();

        $token = $user->createToken('Portpoliwo');

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token->plainTextToken,
            ])
            ->getJson('/api');

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
            ]);
    }
}
