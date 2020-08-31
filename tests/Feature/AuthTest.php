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

    /** test */
    public function test_unauthorized_access()
    {
        $response = $this->getJson('/api');

        $response
            ->assertUnauthorized()
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.'
            ]);
    }

    /** test */
    public function test_registered_user_can_login()
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
