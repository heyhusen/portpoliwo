<?php

namespace Tests\Feature\Portfolio;

use App\Models\Portfolio\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class TagModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $table = 'portfolio_tags';
    protected $url = '/api/portfolio/tag';

    /**
     * Create dummy content
     *
     * @return void
     */
    public function dummyContent()
    {
        return [
            'name' => 'Laravel',
            'slug' => 'laravel'
        ];
    }

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreatePortfolioTag()
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
    public function testUpdatePortfolioTag()
    {
        factory(Tag::class)->create();

        $portfolioTag = Tag::first();
        $portfolioTag->fill($this->dummyContent());
        $portfolioTag->save();

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeletePortfolioTag()
    {
        factory(Tag::class)->create();

        $portfolioTag = Tag::first();
        $portfolioTag->delete();

        $this->assertDeleted($portfolioTag);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedCreatePortfolioTagFromApi()
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
    public function testSuccessfullCreatePortfolioTagFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url, [
            'name' => 'Vue.js'
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully created.',
                'data' => [
                    'name' => 'Vue.js',
                    'slug' => 'vuejs'
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
    public function testFailedReadPortfolioTagFromApi()
    {
        (new Auth())->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson($this->url . '/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\Portfolio\Tag] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing record through API
     *
     * @return void
     */
    public function testSuccessfullReadPortfolioTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create($this->dummyContent());
        $portfolioTag = Tag::first();

        $response = $this->getJson($this->url . '/' . $portfolioTag->id);

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
    public function testFailedUpdatePortfolioTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create();
        $portfolioTag = Tag::first();

        $response = $this->putJson($this->url . '/' . $portfolioTag->id, [
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
    public function testSuccessfullyUpdatePortfolioTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create();
        $portfolioTag = Tag::first();

        $response = $this->putJson($this->url . '/' . $portfolioTag->id, $this->dummyContent());

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
    public function testDeletePortfolioTagFromApi()
    {
        (new Auth())->createAuth();

        factory(Tag::class)->create();
        $portfolioTag = Tag::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$portfolioTag->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data successfully deleted.'
            ]);

        $this->assertDeleted($portfolioTag);
    }
}
