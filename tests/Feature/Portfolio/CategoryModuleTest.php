<?php

namespace Tests\Feature\Portfolio;

use App\Models\Portfolio\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class CategoryModuleTest extends TestCase
{
	use RefreshDatabase;

	protected $table = 'portfolio_categories';
	protected $url = '/api/portfolio/category';

	public function dummyContent()
	{
		return [
			'name' => 'Backend',
			'slug' => 'backend'
		];
	}

	public function testCreatePortfolioCategory()
	{
		Category::factory()->create($this->dummyContent());

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testUpdatePortfolioCategory()
	{
		Category::factory()->create();

		$portfolioCategory = Category::first();
		$portfolioCategory->fill($this->dummyContent());
		$portfolioCategory->save();

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testDeletePortfolioCategory()
	{
		Category::factory()->create();

		$portfolioCategory = Category::first();
		$portfolioCategory->delete();

		$this->assertDeleted($portfolioCategory);
	}

	public function testFailedCreatePortfolioCategoryFromApi()
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

	public function testSuccessfullCreatePortfolioCategoryFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson($this->url, [
			'name' => 'Frontend'
		]);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => [
					'name' => 'Frontend',
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

	public function testFailedReadPortfolioCategoryFromApi()
	{
		(new Auth())->createAuth();

		$uuid = Str::uuid();

		$response = $this->getJson($this->url . '/' . $uuid);

		$response->assertNotFound()
			->assertJson([
				'success' => false,
				'message' => 'No query results for model [App\\Models\\Portfolio\Category] ' . $uuid
			]);
	}

	public function testSuccessfullReadPortfolioCategoryFromApi()
	{
		(new Auth())->createAuth();

		Category::factory()->create($this->dummyContent());
		$portfolioCategory = Category::first();

		$response = $this->getJson($this->url . '/' . $portfolioCategory->id);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'data' => $this->dummyContent()
			]);
	}

	public function testFailedUpdatePortfolioCategoryFromApi()
	{
		(new Auth())->createAuth();

		Category::factory()->create();
		$portfolioCategory = Category::first();

		$response = $this->putJson($this->url . '/' . $portfolioCategory->id, [
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

	public function testSuccessfullyUpdatePortfolioCategoryFromApi()
	{
		(new Auth())->createAuth();

		Category::factory()->create();
		$portfolioCategory = Category::first();

		$response = $this->putJson($this->url . '/' . $portfolioCategory->id, $this->dummyContent());

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => $this->dummyContent()
			]);
	}

	public function testDeletePortfolioCategoryFromApi()
	{
		(new Auth())->createAuth();

		Category::factory()->create();
		$portfolioCategory = Category::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$portfolioCategory->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertDeleted($portfolioCategory);
	}
}
