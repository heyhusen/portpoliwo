<?php

namespace Tests\Feature\Blog;

use App\Models\Blog\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class CategoryModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $table = 'blog_categories';
    protected $url = '/api/blog/category';

    public function dummyContent()
    {
        return [
            'title' => 'Backend',
            'slug' => 'backend',
            'description' => 'A type of programmer who creates the logical back-end and
            core computational logic of a website, software or information system.'
        ];
    }

    public function testCreateBlogCategory()
    {
        Category::factory()->create($this->dummyContent());

        $this->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    public function testUpdateBlogCategory()
    {
        Category::factory()->create();

        $blogCategory = Category::first();
        $blogCategory->fill($this->dummyContent());
        $blogCategory->save();

        $this->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    public function testDeleteBlogCategory()
    {
        Category::factory()->create();

        $blogCategory = Category::first();
        $blogCategory->delete();

        $this->assertDeleted($blogCategory);
    }

    public function testFailedCreateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url);

        $response->assertUnprocessable()
        	->assertInvalid(['title'])
            ->assertJson([
                'success' => false,
                'message' => trans($this->invalidMessage),
                'errors' => [
                    'title' => [trans('validation.required', [
                    	'attribute' => 'title'
                    ])]
                ]
            ]);
    }

    public function testSuccessfullCreateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url, [
            'title' => 'Frontend'
        ]);

        $response->assertCreated()
        	->assertValid()
            ->assertJson([
                'success' => true,
                'message' => trans($this->createdMessage),
                'data' => [
                    'title' => 'Frontend',
                    'slug' => 'frontend'
                ]
            ]);

        $response = $this->postJson($this->url, $this->dummyContent());

        $response->assertCreated()
        	->assertValid()
            ->assertJson([
                'success' => true,
                'message' => trans($this->createdMessage),
                'data' => $this->dummyContent()
            ]);

        $response = $this->postJson($this->url, $this->dummyContent());

        $this->assertDatabaseCount($this->table, 2);

        $response->assertUnprocessable()
        	->assertInvalid(['title', 'slug'])
            ->assertJson([
                'success' => false,
                'message' => trans($this->invalidMessage),
                'errors' => [
                    'title' => [trans('validation.unique', [
                    	'attribute' => 'title'
                    ])],
                    'slug' => [trans('validation.unique', [
                    	'attribute' => 'slug'
                    ])]
                ]
            ]);
    }

    public function testFailedReadBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson($this->url . '/' . $uuid);

        $response->assertNotFound()
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\Blog\Category] ' . $uuid
            ]);
    }

    public function testSuccessfullReadBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        Category::factory()->create($this->dummyContent());
        $blogCategory = Category::first();

        $response = $this->getJson($this->url . '/' . $blogCategory->id);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => $this->dummyContent()
            ]);
    }

    public function testFailedUpdateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        Category::factory()->create();
        $blogCategory = Category::first();

        $response = $this->putJson($this->url . '/' . $blogCategory->id, [
            'title' => '',
            'slug' => '',
            'description' => ''
        ]);

        $response->assertUnprocessable()
        	->assertInvalid(['title'])
            ->assertJson([
                'success' => false,
                'message' => trans($this->invalidMessage),
                'errors' => [
                    'title' => [trans('validation.required', [
                    	'attribute' => 'title'
                    ])],
                ]
            ]);
    }

    public function testSuccessfullyUpdateBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        Category::factory()->create();
        $blogCategory = Category::first();

        $response = $this->putJson($this->url . '/' . $blogCategory->id, $this->dummyContent());

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => trans($this->updatedMessage),
                'data' => $this->dummyContent()
            ]);
    }

    public function testDeleteBlogCategoryFromApi()
    {
        (new Auth())->createAuth();

        Category::factory()->create();
        $blogCategory = Category::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$blogCategory->id]
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => trans($this->deletedMessage)
            ]);

        $this->assertDeleted($blogCategory);
    }
}
