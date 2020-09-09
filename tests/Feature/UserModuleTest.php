<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RolesTableSeeder;
use Tests\Feature\Auth;
use Tests\TestCase;

class UserModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create role with seeder
     *
     * @return void
     */
    public function testCreateRole()
    {
        $this->seed(RolesTableSeeder::class);
        $this
            ->assertDatabaseCount('roles', 3)
            ->assertDatabaseHas('roles', [
                'name' => 'supersu',
                'name' => 'admin',
                'name' => 'user'
            ]);
    }

    /**
     * Test creating an user with role using factory
     * Roles are created using seeder
     *
     * @return void
     */
    public function testCreateUser()
    {
        $this->seed(RolesTableSeeder::class);
        $auth = new Auth();
        $auth
            ->createUser()
            ->each(function ($user) {
                $user->assignRole('user');
            });

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseCount('model_has_roles', 1)
            ->assertDatabaseHas('model_has_roles', [
                'model_type' => 'App\Models\User'
            ]);
    }

    /**
     * Test updating a created user with role
     * Roles are created using seeder
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $this->seed(RolesTableSeeder::class);
        $auth = new Auth();
        $auth
           ->createUser()
           ->each(function ($user) {
                $user->assignRole('user');
            });

        $user = User::first();
        $user->fill([
            'name' => 'Ahmad Husen',
        ]);
        $user->save();

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', [
                'name' => 'Ahmad Husen'
            ])
            ->assertDatabaseCount('model_has_roles', 1)
            ->assertDatabaseHas('model_has_roles', [
                'model_type' => 'App\Models\User'
            ]);
    }

    /**
     * Test deleting a created user with role
     * Roles are created using seeder
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $this->seed(RolesTableSeeder::class);
        $auth = new Auth(['name' => 'Ahmad Husen']);
        $user = $auth
           ->createUser()
           ->each(function ($user) {
                $user->assignRole('user');
            });

        $user = User::first();
        $user->delete();

        $this->assertDeleted($user);
    }

    /**
     * Test creating an user through API with validation
     *
     * @return void
     */
    public function testFailedCreateUserFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $response = $this->postJson('/api/account');

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                    'password_repeat' => ['The password repeat field is required.']
                ]
            ]);
    }

    /**
     * Test creating an user through API
     *
     * @return void
     */
    public function testSuccessfullCreateUserFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $response = $this->postJson('/api/account', [
            'name' => 'Ahmad Husen',
            'email' => 'husen@portpoliwo.app',
            'password' => 'EverybodyKnows',
            'password_repeat' => 'EverybodyKnows'
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => [
                    'name' => 'Ahmad Husen',
                    'email' => 'husen@portpoliwo.app'
                ]
            ]);
        // Check default avatar (gravatar)
        $this->assertStringContainsString('gravatar', $response['data']['avatar']);
    }

    /**
     * Test creating an user with avatar through API
     *
     * @return void
     */
    public function testSuccessfullCreateUserWithAvatarFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        Storage::fake('local');

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/account', [
            'name' => 'Ahmad Husen',
            'email' => 'husen@portpoliwo.app',
            'password' => 'EverybodyKnows',
            'password_repeat' => 'EverybodyKnows',
            'photo' => $avatar
        ]);

        Storage::disk('local')->assertExists('/public/avatar/' . $response['data']['photo']);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => [
                    'name' => 'Ahmad Husen',
                    'email' => 'husen@portpoliwo.app'
                ]
            ]);
        // Check custom avatar
        $this->assertStringNotContainsString('gravatar', $response['data']['avatar']);
    }

    /**
     * Test failed reading an existing user through API
     *
     * @return void
     */
    public function testFailedReadUserFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson('/api/account/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\User] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing user through API
     *
     * @return void
     */
    public function testSuccessfullReadUserFromApi()
    {
        $auth = new Auth([
            'name' => 'Ahmad Husen',
            'email' => 'husen@portpoliwo.app'
        ]);
        $auth->createAuth();

        $user = User::first();

        $response = $this->getJson('/api/account/' . $user->id);

        $response
            ->assertOk()
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

    /**
     * Test failed updating an existing user through API
     *
     * @return void
     */
    public function testFailedUpdateUserFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $user = User::first();

        $response = $this->putJson('/api/account/' . $user->id, [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_repeat' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password must be at least 8 characters.'],
                    'password_repeat' => ['The password repeat must be at least 8 characters.']
                ]
            ]);
    }

    /**
     * Test successfully updating an existing user through API
     *
     * @return void
     */
    public function testSuccessfullyUpdateUserFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $user = User::first();

        $response = $this->putJson('/api/account/' . $user->id, [
            'name' => 'Ahmad Husen',
            'email' => 'husen@portpoliwo.app'
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully updated.',
                'data' => [
                    'name' => 'Ahmad Husen',
                    'email' => 'husen@portpoliwo.app'
                ]
            ]);
    }

    /**
     * Test successfully updating an existing user through API
     *
     * @return void
     */
    public function testSuccessfullyUpdateUserWithAvatarFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $user = User::first();

        Storage::fake('local');

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->putJson('/api/account/' . $user->id, [
            'name' => 'Ahmad Husen',
            'email' => 'husen@portpoliwo.app',
            'photo' => $avatar
        ]);

        Storage::disk('local')->assertExists('/public/avatar/' . $response['data']['photo']);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully updated.',
                'data' => [
                    'name' => 'Ahmad Husen',
                    'email' => 'husen@portpoliwo.app'
                ]
            ]);

        // Check custom avatar
        $this->assertStringNotContainsString('gravatar', $response['data']['avatar']);
    }

    /**
     * Test delete an user
     *
     * @return void
     */
    public function testDeleteUserFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $user = $auth->createUser();

        $response = $this->deleteJson('/api/account/', [
            'selectedData' => [$user->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully deleted.'
            ]);

        $this->assertDeleted($user);
    }

    /**
     * Test delete current logged in user
     * The current user is can not be deleted by default
     *
     * @return void
     */
    public function testDeleteCurrentLoggedInUserFromApi()
    {
        $auth = new Auth([
            'name' => 'Ahmad Husen'
        ]);
        $auth->createAuth();

        $user = User::first();

        $response = $this->deleteJson('/api/account/', [
            'selectedData' => [$user->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully deleted.'
            ]);

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', [
                'name' => 'Ahmad Husen'
            ]);
    }
}
