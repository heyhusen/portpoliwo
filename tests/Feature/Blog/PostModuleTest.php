<?php

namespace Tests\Feature\Blog;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class PostModuleTest extends TestCase
{
	use RefreshDatabase;

	protected $table = 'blog_posts';
	protected $url = '/api/blog';

	public function dummyContent()
	{
		return [
			'title' => 'Morbi sed consequat velit, tempor pretium nunc',
			'slug' => Str::slug('Morbi sed consequat velit, tempor pretium nunc', '-'),
			'summary' => 'Suspendisse euismod tellus nec nisi iaculis pellentesque.
            Curabitur ultricies condimentum orci sed maximus. Nunc maximus nunc quis velit viverra,
            eu condimentum ligula consequat. Proin sem metus, consequat non libero sed,
            vehicula facilisis lectus. Morbi eleifend velit id gravida ultrices.',
			'content' => '<p>Vestibulum sit amet sem quis leo auctor vehicula quis ac sapien.
            Ut nisi odio, dapibus in orci non, dictum finibus odio. Vivamus nulla metus,
            auctor volutpat lobortis nec, aliquam ut mauris. Etiam nec diam nec sapien pellentesque pulvinar.
            Etiam velit nisl, mattis at nisi convallis, posuere vehicula urna.
            Phasellus pharetra tincidunt purus. Donec sit amet sem a libero volutpat elementum.
            Pellentesque non pretium turpis. Phasellus viverra tortor erat, a semper felis congue in.
            Proin consectetur posuere dui eget tincidunt. Nulla fermentum nisi at laoreet dapibus.
            In hac habitasse platea dictumst. Phasellus ac aliquet massa.</p><p>Cras quis vehicula ex,
            at eleifend nisi. Quisque nec lectus sagittis, ornare enim sed, imperdiet felis.
            Vivamus dolor lacus, blandit et molestie at, iaculis at lorem. Suspendisse potenti.
            Suspendisse quis erat dictum, tincidunt dolor at, laoreet eros. Maecenas a mi lectus.
            Suspendisse scelerisque sodales pulvinar. Sed vulputate eleifend quam,
            non luctus felis finibus eget. Fusce elementum, nibh id eleifend consequat,
            erat nisl tempus neque, eget placerat lacus metus non velit. Nunc neque elit,
            scelerisque vitae sodales vitae, iaculis at orci. Cras ut ullamcorper diam,
            ac porttitor diam.</p><p>Vivamus convallis enim vel justo facilisis rutrum.
            Suspendisse mi felis, tempor id quam quis, vulputate tempor neque.
            In hac habitasse platea dictumst. Praesent sed est mi. Nunc pulvinar orci sed molestie rhoncus.
            Proin libero diam, lacinia dignissim maximus quis, eleifend vitae dolor.
            Maecenas feugiat, nisl et luctus molestie, urna turpis congue purus,
            viverra malesuada odio massa sed orci. In ligula leo, cursus vel ultricies in,
            molestie sed felis. Maecenas porta sapien ac dolor ornare vestibulum.
            Etiam tristique, neque nec feugiat faucibus, eros neque rutrum neque,
            in lobortis diam mauris sit amet nisl. Nullam et placerat lorem. Donec et semper mi.
            Proin sed urna a libero malesuada sollicitudin. Curabitur interdum mi ut varius mollis.</p>
            <p>Cras vitae lacinia odio, ac posuere tellus. Ut suscipit felis luctus ligula cursus,
            quis aliquet magna aliquam. Mauris pellentesque nec libero id fermentum.
            Aliquam ut vulputate ligula. Suspendisse ut ipsum sit amet purus fermentum sodales nec vel libero.
            Integer et commodo purus. Pellentesque quis dui imperdiet, pretium nulla sed, maximus erat.
            Nam ac fermentum ligula. Phasellus ornare elit non tellus interdum, sit amet vulputate risus semper.
            Sed eu tortor hendrerit, feugiat orci non, finibus ligula. Quisque accumsan,
            nibh accumsan fermentum dapibus, nisl metus venenatis orci, a eleifend leo felis ut urna.
            Vestibulum porttitor mattis commodo. Phasellus semper, magna in maximus imperdiet,
            est nulla cursus tellus, ut hendrerit massa est et massa. Maecenas fringilla aliquet elit eget rhoncus.
            Nulla facilisi.</p><p>Nam ut purus sit amet dolor imperdiet aliquam. Quisque nisi arcu,
            vehicula eget purus at, volutpat scelerisque mauris. In sed libero in arcu bibendum iaculis et quis sem.
            Vivamus tempor tincidunt urna, et euismod erat faucibus sit amet. Cras sed magna eu odio ultricies tempor.
            Morbi pulvinar eu nisl convallis pharetra. Morbi ac consequat orci.
            Curabitur scelerisque est non lobortis eleifend. Aenean quis tortor condimentum felis fringilla tristique.
            Vivamus imperdiet tincidunt elementum. Fusce hendrerit lorem ut ante lacinia tempus.
            Curabitur libero lacus, vehicula faucibus volutpat vitae, posuere sit amet dolor.
            Duis metus quam, maximus a tincidunt ac, ornare at turpis. Proin nec sagittis neque.
            Nullam rutrum, est nec luctus dignissim, mauris lacus varius augue,
            non vulputate nisi tellus quis purus.</p>'
		];
	}

	public function blogPost($data = [])
	{
		return Post::factory()
			->create($data)
			->each(function ($post) {
				$post->categories()->save(Category::factory()->make());
				$post->tags()->createMany(
					Tag::factory(3)->make()->toArray()
				);
			});
	}

	public function category()
	{
		return Category::factory()->create();
	}

	public function tag()
	{
		return Tag::factory()->create();
	}

	public function testCreateBlogPost()
	{
		$this->blogPost($this->dummyContent());

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testUpdateBlogPost()
	{
		$this->blogPost();

		$blogPost = Post::first();
		$blogPost->fill($this->dummyContent());
		$blogPost->save();

		$this->assertDatabaseCount($this->table, 1)
			->assertDatabaseHas($this->table, $this->dummyContent());
	}

	public function testDeleteBlogPost()
	{
		$blogPost = $this->blogPost();

		$blogPost = Post::first();
		$blogPost->delete();

		$this->assertSoftDeleted($blogPost);

		$blogPost->forceDelete();

		$this->assertDeleted($blogPost);
	}

	public function testFailedCreateBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$response = $this->postJson($this->url);

		$response->assertUnprocessable()
			->assertInvalid([
				'title',
				'content',
				'image',
				'blog_category_id',
				'blog_tag_id'
			])
			->assertJson([
				'success' => false,
				'message' => trans($this->invalidMessage),
				'errors' => [
					'title' => [trans('validation.required', [
						'attribute' => 'title'
					])],
					'content' => [trans('validation.required', [
						'attribute' => 'content'
					])],
					'image' => [trans('validation.required', [
						'attribute' => 'image'
					])],
					'blog_category_id' => [trans('validation.required', [
						'attribute' => 'category'
					])],
					'blog_tag_id' => [trans('validation.required', [
						'attribute' => 'tag'
					])]
				]
			]);
	}

	public function testSuccessfullCreateBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$this->category();
		$blogCategory = Category::pluck('id');

		$this->tag();
		$blogTag = Tag::pluck('id');

		Storage::fake('local');
		$thumbnail = UploadedFile::fake()->image('thumbnail.png');

		$response = $this->postJson($this->url, [
			'title' => $this->dummyContent()['title'] . ' test',
			'content' => $this->dummyContent()['content'],
			'image' => $thumbnail,
			'blog_category_id' => $blogCategory,
			'blog_tag_id' => $blogTag
		]);

		Storage::disk('local')->assertExists('/public/blog/' . $response['data']['image']);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => [
					'title' => $this->dummyContent()['title'] . ' test',
					'slug' => $this->dummyContent()['slug'] . '-test',
					'content' => $this->dummyContent()['content'],
				]
			]);

		$response = $this->postJson($this->url, array_merge($this->dummyContent(), [
			'image' => $thumbnail,
			'blog_category_id' => $blogCategory,
			'blog_tag_id' => $blogTag
		]));

		Storage::disk('local')->assertExists('/public/blog/' . $response['data']['image']);

		$response->assertCreated()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->createdMessage),
				'data' => $this->dummyContent()
			]);

		$response = $this->postJson($this->url, array_merge($this->dummyContent(), [
			'image' => $thumbnail,
			'blog_category_id' => $blogCategory,
			'blog_tag_id' => $blogTag
		]));

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

	public function testFailedReadBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$uuid = Str::uuid();

		$response = $this->getJson($this->url . '/' . $uuid);

		$response->assertNotFound()
			->assertJson([
				'success' => false,
				'message' => 'No query results for model [App\\Models\\Blog\Post] ' . $uuid
			]);
	}

	public function testSuccessfullReadBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$this->blogPost($this->dummyContent());
		$blogPost = Post::first();

		$response = $this->getJson($this->url . '/' . $blogPost->id);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'data' => $this->dummyContent()
			]);
	}

	public function testFailedUpdateBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$this->blogPost();
		$blogPost = Post::first();

		$response = $this->putJson($this->url . '/' . $blogPost->id, [
			'title' => '',
			'slug' => '',
			'content' => ''
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

	public function testSuccessfullyUpdateBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$this->blogPost();
		$blogPost = Post::first();

		$this->category();
		$blogCategory = Category::pluck('id');

		$this->tag();
		$blogTag = Tag::pluck('id');

		Storage::fake('local');
		$thumbnail = UploadedFile::fake()->image('thumbnail.png');

		$response = $this->putJson($this->url . '/' . $blogPost->id, array_merge($this->dummyContent(), [
			'image' => $thumbnail,
			'blog_category_id' => $blogCategory,
			'blog_tag_id' => $blogTag
		]));

		Storage::disk('local')->assertExists('/public/blog/' . $response['data']['image']);

		$response->assertOk()
			->assertValid()
			->assertJson([
				'success' => true,
				'message' => trans($this->updatedMessage),
				'data' => $this->dummyContent()
			]);
	}

	public function testDeleteBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$this->blogPost();
		$blogPost = Post::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$blogPost->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertSoftDeleted($blogPost);
	}

	public function testRestoreDeletedBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$this->blogPost($this->dummyContent());
		$blogPost = Post::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$blogPost->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertSoftDeleted($blogPost);

		$response = $this->postJson($this->url . '/restore', [
			'selectedData' => [$blogPost->id]
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->restoredMessage)
			]);
		$this->assertDatabaseCount($this->table, 1);
	}

	public function testPermanentDeleteBlogPostFromApi()
	{
		(new Auth())->createAuth();

		$this->blogPost();
		$blogPost = Post::first();

		$response = $this->deleteJson($this->url, [
			'selectedData' => [$blogPost->id],
			'permanent' => true
		]);

		$response->assertOk()
			->assertJson([
				'success' => true,
				'message' => trans($this->deletedMessage)
			]);

		$this->assertDeleted($blogPost);
	}
}
