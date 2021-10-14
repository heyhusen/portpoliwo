<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class Auth extends TestCase
{
    protected $data;

    /**
     * Construct class
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * Create user with model factory
     *
     * @return void
     */
    public function createUser()
    {
        $user = User::factory()->create($this->data);
        return $user;
    }

    /**
     * Create auth with Sanctum
     *
     * @return void
     */
    public function createAuth()
    {
        $auth = Sanctum::actingAs($this->createUser($this->data));
        return $auth;
    }
}
