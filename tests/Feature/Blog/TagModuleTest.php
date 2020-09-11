<?php

namespace Tests\Feature\Blog;

use App\Models\Blog\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class TagModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $table = 'blog_tags';
    protected $url = '/api/blog/tag';

    /**
     * Create dummy content
     *
     * @return void
     */
    public function dummyContent()
    {
        return [
            'title' => 'Laravel',
            'slug' => 'laravel',
            'description' => 'Open-source PHP web framework, created by Taylor Otwell and
            intended for the development of web applications following the model–view–controller
            architectural pattern and based on Symfony.'
        ];
    }

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreateBlogTag()
    {
        factory(Tag::class)->create($this->dummyContent());

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test updating a record
     *
     * @return void
     */
    public function testUpdateBlogTag()
    {
        factory(Tag::class)->create();

        $blogTag = Tag::first();
        $blogTag->fill($this->dummyContent());
        $blogTag->save();

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeleteBlogTag()
    {
        factory(Tag::class)->create();

        $blogTag = Tag::first();
        $blogTag->delete();

        $this->assertDeleted($blogTag);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedCreateBlogTagFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => __('The given data was invalid.'),
                'errors' => [
                    'title' => [__('The title field is required.')]
                ]
            ]);
    }

    /**
     * Test creating a record through API
     *
     * @return void
     */
    public function testSuccessfullCreateBlogTagFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url, [
            'title' => 'Vue.js'
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => [
                    'title' => 'Vue.js',
                    'slug' => 'vuejs'
                ]
            ]);

        $response = $this->postJson($this->url, $this->dummyContent());

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully created.'),
                'data' => $this->dummyContent()
            ]);

        $response = $this->postJson($this->url, $this->dummyContent());

        $this->assertDatabaseCount($this->table, 2);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => __('The given data was invalid.'),
                'errors' => [
                    'title' => [__('The title has already been taken.')],
                    'slug' => [__('The slug has already been taken.')]
                ]
            ]);
    }

    /**
     * Test failed reading an existing record through API
     *
     * @return void
     */
    public function testFailedReadBlogTagFromApi()
    {
        (new Auth())->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson($this->url . '/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\Blog\Tag] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing record through API
     *
     * @return void
     */
    public function testSuccessfullReadBlogTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create($this->dummyContent());
        $blogTag = Tag::first();

        $response = $this->getJson($this->url . '/' . $blogTag->id);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'data' => $this->dummyContent()
            ]);
    }

    /**
     * Test failed updating an existing record through API
     *
     * @return void
     */
    public function testFailedUpdateBlogTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create();
        $blogTag = Tag::first();

        $response = $this->putJson($this->url . '/' . $blogTag->id, [
            'title' => '',
            'slug' => '',
            'description' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => __('The given data was invalid.'),
                'errors' => [
                    'title' => [__('The title field is required.')],
                ]
            ]);
    }

    /**
     * Test successfully updating an existing record through API
     *
     * @return void
     */
    public function testSuccessfullyUpdateBlogTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create();
        $blogTag = Tag::first();

        $response = $this->putJson($this->url . '/' . $blogTag->id, $this->dummyContent());

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully updated.'),
                'data' => $this->dummyContent()
            ]);
    }

    /**
     * Test delete a record
     *
     * @return void
     */
    public function testDeleteBlogTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create();
        $blogTag = Tag::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$blogTag->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertDeleted($blogTag);
    }
}
