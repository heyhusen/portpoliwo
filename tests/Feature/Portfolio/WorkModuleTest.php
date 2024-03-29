<?php

namespace Tests\Feature\Portfolio;

use App\Models\Portfolio\Category;
use App\Models\Portfolio\Tag;
use App\Models\Portfolio\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class WorkModuleTest extends TestCase
{
	use RefreshDatabase;

	protected $table = 'portfolio_works';
	protected $url = '/api/portfolio';

	public function dummyContent()
	{
		return [
			'name' => 'Aliquam lobortis tincidunt orci, et.',
			'description' => '<p>Nulla sed nisi vitae justo faucibus luctus. Nulla ultricies sollicitudin sem,
            sed tincidunt lorem luctus et. Ut viverra imperdiet leo, id rutrum risus facilisis eget.
            Integer et lorem tincidunt, pellentesque augue quis, tristique arcu.
            Curabitur maximus ex eget diam suscipit facilisis. Cras tincidunt mauris mi,
            a vestibulum dolor euismod quis. Etiam iaculis blandit diam, vel condimentum felis lobortis quis.
            Duis lacus nisl, feugiat ut sem et, varius malesuada mi.</p><p>Curabitur egestas,
            eros nec maximus pretium, leo sapien suscipit sem, sed lacinia turpis felis varius est.
            Cras cursus, massa a imperdiet tempor, eros justo facilisis quam, quis dictum eros sapien interdum ex.
            Nunc sit amet sem vitae est euismod ullamcorper eget nec nunc.
            Vestibulum non orci a nisl laoreet pellentesque in ut diam. Integer molestie,
            mi ut fermentum facilisis, libero ipsum faucibus nisi, sed egestas lectus augue sit amet ex.
            Aenean et leo blandit, dapibus orci sit amet, placerat libero. Nulla interdum eu est in bibendum.</p>
            <p>Nulla facilisi. Quisque vestibulum massa ut tellus viverra maximus.
            Vestibulum ultrices maximus congue. Maecenas varius eleifend ipsum, ornare porta dolor posuere at.
            Sed bibendum augue at ullamcorper consectetur. Duis at cursus erat. Nunc in sem nisi.
            Donec efficitur ipsum non tortor convallis, et interdum metus aliquet. Proin eu turpis mi.</p>',
			'url' => 'https://github.com/hapakaien/portpoliwo'
		];
	}

	public function createWork($data = [])
	{
		return Work::factory()
			->create($data)
			->each(function ($work) {
				$work->categories()->save(Category::factory()->make());
				$work->tags()->createMany(
					Tag::factory(3)->make()->toArray()
				);
			});
	}

	public function createCategory()
	{
		return Category::factory()->create();
	}

	public function createTag()
	{
		return Tag::factory()->create();
	}

	public function testCreateWork()
	{
		$this->createWork($this->dummyContent());

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testUpdateWork()
	{
		$this->createWork();

		$Work = Work::first();
		$Work->fill($this->dummyContent());
		$Work->save();

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testDeleteWork()
	{
		$Work = $this->createWork();

		$Work = Work::first();
		$Work->delete();

		$this->assertSoftDeleted($Work);

		$Work->forceDelete();

		$this->assertDeleted($Work);
	}

	public function testFailedCreateWorkFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson($this->url);

		$response->assertUnprocessable()
			->assertInvalid([
				'name',
				'description',
				'category_id',
				'tag_id'
			])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])],
					'description' => [trans('validation.required', [
						'attribute' => 'description'
					])],
					'category_id' => [trans('validation.required', [
						'attribute' => 'category'
					])],
					'tag_id' => [trans('validation.required', [
						'attribute' => 'tag'
					])]
				]
			]);
	}

	public function testSuccessfullCreateWorkFromApi()
	{
		(new Auth())->createAuth();

		$this->createCategory();
		$portfolioCategory = Category::pluck('id');

		$this->createTag();
		$portfolioTag = Tag::pluck('id');

		Storage::fake('local');
		$photo = UploadedFile::fake()->image('photo.png');

		$response = $this->postJson($this->url, array_merge($this->dummyContent(), [
			'photo' => $photo,
			'category_id' => $portfolioCategory,
			'tag_id' => $portfolioTag
		]));

		Storage::assertExists('/public/portfolio/' . $response['data']['photo']);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => $this->dummyContent()
			]);

		$response = $this->postJson($this->url, array_merge($this->dummyContent(), [
			'photo' => $photo,
			'category_id' => $portfolioCategory,
			'tag_id' => $portfolioTag
		]));

		$response->assertUnprocessable()
			->assertInvalid(['name'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.unique', [
						'attribute' => 'name'
					])]
				]
			]);
	}

	public function testFailedReadWorkFromApi()
	{
		(new Auth())->createAuth();

		$uuid = Str::uuid();

		$response = $this->getJson($this->url . '/' . $uuid);

		$response->assertNotFound()
			->assertJson([
				'success' => false,
				'message' => 'No query results for model [App\\Models\\Portfolio\Work] ' . $uuid
			]);
	}

	public function testSuccessfullReadWorkFromApi()
	{
		(new Auth())->createAuth();

		$this->createWork($this->dummyContent());
		$Work = Work::first();

		$response = $this->getJson($this->url . '/' . $Work->id);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'data' => $this->dummyContent()
			]);
	}

	public function testFailedUpdateWorkFromApi()
	{
		(new Auth())->createAuth();

		$this->createWork();
		$Work = Work::first();

		$response = $this->putJson($this->url . '/' . $Work->id, [
			'name' => '',
			'description' => ''
		]);

		$response->assertUnprocessable()
			->assertInvalid(['name', 'description'])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'name' => [trans('validation.required', [
						'attribute' => 'name'
					])],
					'description' => [trans('validation.required', [
						'attribute' => 'description'
					])]
				]
			]);
	}

	public function testSuccessfullyUpdateWorkFromApi()
	{
		(new Auth())->createAuth();

		$this->createWork();
		$Work = Work::first();

		$this->createCategory();
		$portfolioCategory = Category::pluck('id');

		$this->createTag();
		$portfolioTag = Tag::pluck('id');

		Storage::fake('local');
		$photo = UploadedFile::fake()->image('photo.png');

		$response = $this->putJson($this->url . '/' . $Work->id, array_merge($this->dummyContent(), [
			'photo' => $photo,
			'category_id' => $portfolioCategory,
			'tag_id' => $portfolioTag
		]));

		Storage::assertExists('/public/portfolio/' . $response['data']['photo']);

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => $this->dummyContent()
			]);
	}

	public function testDeleteWorkFromApi()
	{
		(new Auth())->createAuth();

		$this->createWork();
		$Work = Work::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$Work->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertSoftDeleted($Work);
	}

	public function testRestoreDeletedWorkFromApi()
	{
		(new Auth())->createAuth();

		$this->createWork($this->dummyContent());
		$Work = Work::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$Work->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertSoftDeleted($Work);

		$response = $this->postJson($this->url . '/restore', [
			'selectedData' => [$Work->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->restoredMessage)
			]);
		$this->assertDatabaseCount($this->table, 1);
	}

	public function testPermanentDeleteWorkFromApi()
	{
		(new Auth())->createAuth();

		$this->createWork();
		$Work = Work::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$Work->id],
			'permanent' => true
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertDeleted($Work);
	}
}
