<?php

namespace Tests\Feature\Portfolio;

use App\Models\Portfolio\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class TagModuleTest extends TestCase
{
	use RefreshDatabase;

	protected $table = 'portfolio_tags';
	protected $url = '/api/portfolio/tag';

	public function dummyContent()
	{
		return [
			'name' => 'Laravel',
			'slug' => 'laravel'
		];
	}

	public function testCreatePortfolioTag()
	{
		Tag::factory()->create($this->dummyContent());

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testUpdatePortfolioTag()
	{
		Tag::factory()->create();

		$portfolioTag = Tag::first();
		$portfolioTag->fill($this->dummyContent());
		$portfolioTag->save();

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testDeletePortfolioTag()
	{
		Tag::factory()->create();

		$portfolioTag = Tag::first();
		$portfolioTag->delete();

		$this->assertDeleted($portfolioTag);
	}

	public function testFailedCreatePortfolioTagFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson($this->url);

		$response->assertUnprocessable()
			->assertInvalid(['name'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])]
				]
			]);
	}

	public function testSuccessfullCreatePortfolioTagFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson($this->url, [
			'name' => 'Vue.js'
		]);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => [
					'name' => 'Vue.js',
					'slug' => 'vuejs'
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
			->assertInvalid(['name', 'slug'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.unique', [
						'attribute' => 'name'
					])],
					'slug' => [trans('validation.unique', [
						'attribute' => 'slug'
					])]
				]
			]);
	}

	public function testFailedReadPortfolioTagFromApi()
	{
		(new Auth())->createAuth();

		$uuid = Str::uuid();

		$response = $this->getJson($this->url . '/' . $uuid);

		$response->assertNotFound()
			->assertJson([
				'success' => false,
				'message' => 'No query results for model [App\\Models\\Portfolio\Tag] ' . $uuid
			]);
	}

	public function testSuccessfullReadPortfolioTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create($this->dummyContent());
		$portfolioTag = Tag::first();

		$response = $this->getJson($this->url . '/' . $portfolioTag->id);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'data' => $this->dummyContent()
			]);
	}

	public function testFailedUpdatePortfolioTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create();
		$portfolioTag = Tag::first();

		$response = $this->putJson($this->url . '/' . $portfolioTag->id, [
			'name' => '',
			'slug' => ''
		]);

		$response->assertUnprocessable()
			->assertInvalid(['name'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])],
				]
			]);
	}

	public function testSuccessfullyUpdatePortfolioTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create();
		$portfolioTag = Tag::first();

		$response = $this->putJson($this->url . '/' . $portfolioTag->id, $this->dummyContent());

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => $this->dummyContent()
			]);
	}

	public function testDeletePortfolioTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create();
		$portfolioTag = Tag::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$portfolioTag->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertDeleted($portfolioTag);
	}
}
