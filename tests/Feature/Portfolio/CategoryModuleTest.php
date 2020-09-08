<?php

namespace Tests\Feature\Portfolio;

use App\Models\Portfolio\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class CategoryModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $table = 'portfolio_categories';
    protected $url = '/api/portfolio/category';

    /**
     * Create dummy content
     *
     * @return void
     */
    public function dummyContent()
    {
        return [
            'name' => 'Backend',
            'slug' => 'backend'
        ];
    }

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreatePortfolioCategory()
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
    public function testUpdatePortfolioCategory()
    {
        factory(Category::class)->create();

        $portfolioCategory = Category::first();
        $portfolioCategory->fill($this->dummyContent());
        $portfolioCategory->save();

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeletePortfolioCategory()
    {
        factory(Category::class)->create();

        $portfolioCategory = Category::first();
        $portfolioCategory->delete();

        $this->assertDeleted($portfolioCategory);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedCreatePortfolioCategoryFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.']
                ]
            ]);
    }

    /**
     * Test creating a record through API
     *
     * @return void
     */
    public function testSuccessfullCreatePortfolioCategoryFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url, [
            'name' => 'Frontend'
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => [
                    'name' => 'Frontend',
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
                    'name' => ['The name has already been taken.'],
                    'slug' => ['The slug has already been taken.']
                ]
            ]);
    }

    /**
     * Test failed reading an existing record through API
     *
     * @return void
     */
    public function testFailedReadPortfolioCategoryFromApi()
    {
        (new Auth())->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson($this->url . '/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\Portfolio\Category] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing record through API
     *
     * @return void
     */
    public function testSuccessfullReadPortfolioCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create($this->dummyContent());
        $portfolioCategory = Category::first();

        $response = $this->getJson($this->url . '/' . $portfolioCategory->id);

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
    public function testFailedUpdatePortfolioCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create();
        $portfolioCategory = Category::first();

        $response = $this->putJson($this->url . '/' . $portfolioCategory->id, [
            'name' => '',
            'slug' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                ]
            ]);
    }

    /**
     * Test successfully updating an existing record through API
     *
     * @return void
     */
    public function testSuccessfullyUpdatePortfolioCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create();
        $portfolioCategory = Category::first();

        $response = $this->putJson($this->url . '/' . $portfolioCategory->id, $this->dummyContent());

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
    public function testDeletePortfolioCategoryFromApi()
    {
        (new Auth())->createAuth();

        factory(Category::class)->create();
        $portfolioCategory = Category::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$portfolioCategory->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully deleted.'
            ]);

        $this->assertDeleted($portfolioCategory);
    }
}
