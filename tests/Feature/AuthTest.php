<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
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
    public function testRegisteredUserCanLogIn()
    {
        Sanctum::actingAs(
            factory(User::class)->create()
        );

        $response = $this->getJson('/api');

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
            ]);
    }
}
