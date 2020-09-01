<?php

namespace Tests\Feature;

use App\Models\SocialMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class SocialMediaModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreateSocialMedia()
    {
        factory(SocialMedia::class)->create([
            'name' => 'Github',
            'icon' => 'github',
            'url' => 'https://github.com/husenisme'
        ]);

        $this
            ->assertDatabaseCount('social_medias', 1)
            ->assertDatabaseHas('social_medias', [
                'name' => 'Github',
                'icon' => 'github',
                'url' => 'https://github.com/husenisme'
            ]);
    }

    /**
     * Test updating a record
     *
     * @return void
     */
    public function testUpdateSocialMedia()
    {
        factory(SocialMedia::class)->create();

        $socialMedia = SocialMedia::first();
        $socialMedia->fill([
            'name' => 'Github',
            'icon' => 'github',
            'url' => 'https://github.com/husenisme'
        ]);
        $socialMedia->save();

        $this
            ->assertDatabaseCount('social_medias', 1)
            ->assertDatabaseHas('social_medias', [
                'name' => 'Github',
                'icon' => 'github',
                'url' => 'https://github.com/husenisme'
            ]);
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeleteSocialMedia()
    {
        factory(SocialMedia::class)->create();

        $socialMedia = SocialMedia::first();
        $socialMedia->delete();

        $this->assertDeleted($socialMedia);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedCreateSocialMediaFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $response = $this->postJson('/api/social-media');

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'icon' => ['The icon field is required.'],
                    'url' => ['The url field is required.']
                ]
            ]);
    }

    /**
     * Test creating a record through API
     *
     * @return void
     */
    public function testSuccessfullCreateSocialMediaFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $response = $this->postJson('/api/social-media', [
            'name' => 'Github',
            'icon' => 'github',
            'url' => 'https://github.com/husenisme'
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => [
                    'name' => 'Github',
                    'icon' => 'github',
                    'url' => 'https://github.com/husenisme'
                ]
            ]);

        $response = $this->postJson('/api/social-media', [
            'name' => 'Github',
            'icon' => 'github',
            'url' => 'https://github.com/husenisme'
        ]);

        $this->assertDatabaseCount('social_medias', 1);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name has already been taken.']
                ]
            ]);
    }

    /**
     * Test failed reading an existing record through API
     *
     * @return void
     */
    public function testFailedReadSocialMediaFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson('/api/social-media/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\SocialMedia] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing record through API
     *
     * @return void
     */
    public function testSuccessfullReadSocialMediaFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        factory(SocialMedia::class)->create([
            'name' => 'Github',
            'icon' => 'github',
            'url' => 'https://github.com/husenisme'
        ]);
        $socialMedia = SocialMedia::first();

        $response = $this->getJson('/api/social-media/' . $socialMedia->id);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Github',
                    'icon' => 'github',
                    'url' => 'https://github.com/husenisme'
                ]
            ]);
    }

    /**
     * Test failed updating an existing record through API
     *
     * @return void
     */
    public function testFailedUpdateSocialMediaFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        factory(SocialMedia::class)->create();
        $socialMedia = SocialMedia::first();

        $response = $this->putJson('/api/social-media/' . $socialMedia->id, [
            'name' => '',
            'icon' => '',
            'url' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'icon' => ['The icon field is required.'],
                    'url' => ['The url field is required.'],
                ]
            ]);
    }

    /**
     * Test successfully updating an existing record through API
     *
     * @return void
     */
    public function testSuccessfullyUpdateSocialMediaFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        factory(SocialMedia::class)->create();
        $socialMedia = SocialMedia::first();

        $response = $this->putJson('/api/social-media/' . $socialMedia->id, [
            'name' => 'Github',
            'icon' => 'github',
            'url' => 'https://github.com/husenisme'
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully updated.',
                'data' => [
                    'name' => 'Github',
                    'icon' => 'github',
                    'url' => 'https://github.com/husenisme'
                ]
            ]);
    }

    /**
     * Test delete a record
     *
     * @return void
     */
    public function testDeleteSocialMediaFromApi()
    {
        $auth = new Auth();
        $auth->createAuth();

        factory(SocialMedia::class)->create();
        $socialMedia = SocialMedia::first();

        $response = $this->deleteJson('/api/social-media/', [
            'selectedData' => [$socialMedia->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully deleted.'
            ]);

        $this->assertDeleted($socialMedia);
    }
}
