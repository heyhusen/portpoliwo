<?php

namespace Tests\Feature\Blog;

use App\Models\Blog\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class TagModuleTest extends TestCase
{
	use RefreshDatabase;

	protected $table = 'blog_tags';
	protected $url = '/api/blog/tag';

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

	public function testCreateBlogTag()
	{
		Tag::factory()->create($this->dummyContent());

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testUpdateBlogTag()
	{
		Tag::factory()->create();

		$blogTag = Tag::first();
		$blogTag->fill($this->dummyContent());
		$blogTag->save();

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testDeleteBlogTag()
	{
		Tag::factory()->create();

		$blogTag = Tag::first();
		$blogTag->delete();

		$this->assertDeleted($blogTag);
	}

	public function testFailedCreateBlogTagFromApi()
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

	public function testSuccessfullCreateBlogTagFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson($this->url, [
			'title' => 'Vue.js'
		]);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => [
					'title' => 'Vue.js',
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

	public function testFailedReadBlogTagFromApi()
	{
		(new Auth())->createAuth();

		$uuid = Str::uuid();

		$response = $this->getJson($this->url . '/' . $uuid);

		$response->assertNotFound()
			->assertJson([
				'success' => false,
				'message' => 'No query results for model [App\\Models\\Blog\Tag] ' . $uuid
			]);
	}

	public function testSuccessfullReadBlogTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create($this->dummyContent());
		$blogTag = Tag::first();

		$response = $this->getJson($this->url . '/' . $blogTag->id);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'data' => $this->dummyContent()
			]);
	}

	public function testFailedUpdateBlogTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create();
		$blogTag = Tag::first();

		$response = $this->putJson($this->url . '/' . $blogTag->id, [
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

	public function testSuccessfullyUpdateBlogTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create();
		$blogTag = Tag::first();

		$response = $this->putJson($this->url . '/' . $blogTag->id, $this->dummyContent());

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => $this->dummyContent()
			]);
	}

	public function testDeleteBlogTagFromApi()
	{
		(new Auth())->createAuth();

		Tag::factory()->create();
		$blogTag = Tag::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$blogTag->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertDeleted($blogTag);
	}
}
