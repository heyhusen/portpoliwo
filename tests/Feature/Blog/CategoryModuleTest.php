<?php

namespace Tests\Feature\Blog;

use App\Models\Blog\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class CategoryModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $table = 'blog_categories';
    protected $url = '/api/blog/category';

    /**
     * Create dummy content
     *
     * @return void
     */
    public function dummyContent()
    {
        return [
            'title' => 'Backend',
            'slug' => 'backend',
            'description' => 'A type of programmer who creates the logical back-end and
            core computational logic of a website, software or information system.'
        ];
    }

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreateBlogCategory()
    {
        factory(Category::class)->create($this->dummyContent());

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test updating a record
     *
     * @return void
     */
    public function testUpdateBlogCategory()
    {
        factory(Category::class)->create();

        $blogCategory = Category::first();
        $blogCategory->fill($this->dummyContent());
        $blogCategory->save();

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeleteBlogCategory()
    {
        factory(Category::class)->create();

        $blogCategory = Category::first();
        $blogCategory->delete();

        $this->assertDeleted($blogCategory);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedCreateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => ['The title field is required.']
                ]
            ]);
    }

    /**
     * Test creating a record through API
     *
     * @return void
     */
    public function testSuccessfullCreateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url, [
            'title' => 'Frontend'
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => [
                    'title' => 'Frontend',
                    'slug' => 'frontend'
                ]
            ]);

        $response = $this->postJson($this->url, $this->dummyContent());

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => $this->dummyContent()
            ]);

        $response = $this->postJson($this->url, $this->dummyContent());

        $this->assertDatabaseCount($this->table, 2);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => ['The title has already been taken.'],
                    'slug' => ['The slug has already been taken.']
                ]
            ]);
    }

    /**
     * Test failed reading an existing record through API
     *
     * @return void
     */
    public function testFailedReadBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson($this->url . '/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\Blog\Category] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing record through API
     *
     * @return void
     */
    public function testSuccessfullReadBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create($this->dummyContent());
        $blogCategory = Category::first();

        $response = $this->getJson($this->url . '/' . $blogCategory->id);

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
    public function testFailedUpdateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create();
        $blogCategory = Category::first();

        $response = $this->putJson($this->url . '/' . $blogCategory->id, [
            'title' => '',
            'slug' => '',
            'description' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => ['The title field is required.'],
                ]
            ]);
    }

    /**
     * Test successfully updating an existing record through API
     *
     * @return void
     */
    public function testSuccessfullyUpdateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create();
        $blogCategory = Category::first();

        $response = $this->putJson($this->url . '/' . $blogCategory->id, $this->dummyContent());

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully updated.',
                'data' => $this->dummyContent()
            ]);
    }

    /**
     * Test delete a record
     *
     * @return void
     */
    public function testDeleteBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create();
        $blogCategory = Category::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$blogCategory->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully deleted.'
            ]);

        $this->assertDeleted($blogCategory);
    }
}
