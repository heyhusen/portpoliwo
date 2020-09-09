<?php

namespace Tests\Feature\Blog;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    /**
     * Set dummy ontent
     *
     * @return void
     */
    public function dummyContent()
    {
        return [
            'title' => 'Morbi sed consequat velit, tempor pretium nunc',
            'slug' => Str::slug('Morbi sed consequat velit, tempor pretium nunc', '-'),
            'summary' => 'Suspendisse euismod tellus nec nisi iaculis pellentesque. Curabitur ultricies condimentum orci sed maximus. Nunc maximus nunc quis velit viverra, eu condimentum ligula consequat. Proin sem metus, consequat non libero sed, vehicula facilisis lectus. Morbi eleifend velit id gravida ultrices.',
            'content' => '<p>Vestibulum sit amet sem quis leo auctor vehicula quis ac sapien. Ut nisi odio, dapibus in orci non, dictum finibus odio. Vivamus nulla metus, auctor volutpat lobortis nec, aliquam ut mauris. Etiam nec diam nec sapien pellentesque pulvinar. Etiam velit nisl, mattis at nisi convallis, posuere vehicula urna. Phasellus pharetra tincidunt purus. Donec sit amet sem a libero volutpat elementum. Pellentesque non pretium turpis. Phasellus viverra tortor erat, a semper felis congue in. Proin consectetur posuere dui eget tincidunt. Nulla fermentum nisi at laoreet dapibus. In hac habitasse platea dictumst. Phasellus ac aliquet massa.</p><p>Cras quis vehicula ex, at eleifend nisi. Quisque nec lectus sagittis, ornare enim sed, imperdiet felis. Vivamus dolor lacus, blandit et molestie at, iaculis at lorem. Suspendisse potenti. Suspendisse quis erat dictum, tincidunt dolor at, laoreet eros. Maecenas a mi lectus. Suspendisse scelerisque sodales pulvinar. Sed vulputate eleifend quam, non luctus felis finibus eget. Fusce elementum, nibh id eleifend consequat, erat nisl tempus neque, eget placerat lacus metus non velit. Nunc neque elit, scelerisque vitae sodales vitae, iaculis at orci. Cras ut ullamcorper diam, ac porttitor diam.</p><p>Vivamus convallis enim vel justo facilisis rutrum. Suspendisse mi felis, tempor id quam quis, vulputate tempor neque. In hac habitasse platea dictumst. Praesent sed est mi. Nunc pulvinar orci sed molestie rhoncus. Proin libero diam, lacinia dignissim maximus quis, eleifend vitae dolor. Maecenas feugiat, nisl et luctus molestie, urna turpis congue purus, viverra malesuada odio massa sed orci. In ligula leo, cursus vel ultricies in, molestie sed felis. Maecenas porta sapien ac dolor ornare vestibulum. Etiam tristique, neque nec feugiat faucibus, eros neque rutrum neque, in lobortis diam mauris sit amet nisl. Nullam et placerat lorem. Donec et semper mi. Proin sed urna a libero malesuada sollicitudin. Curabitur interdum mi ut varius mollis.</p><p>Cras vitae lacinia odio, ac posuere tellus. Ut suscipit felis luctus ligula cursus, quis aliquet magna aliquam. Mauris pellentesque nec libero id fermentum. Aliquam ut vulputate ligula. Suspendisse ut ipsum sit amet purus fermentum sodales nec vel libero. Integer et commodo purus. Pellentesque quis dui imperdiet, pretium nulla sed, maximus erat. Nam ac fermentum ligula. Phasellus ornare elit non tellus interdum, sit amet vulputate risus semper. Sed eu tortor hendrerit, feugiat orci non, finibus ligula. Quisque accumsan, nibh accumsan fermentum dapibus, nisl metus venenatis orci, a eleifend leo felis ut urna. Vestibulum porttitor mattis commodo. Phasellus semper, magna in maximus imperdiet, est nulla cursus tellus, ut hendrerit massa est et massa. Maecenas fringilla aliquet elit eget rhoncus. Nulla facilisi.</p><p>Nam ut purus sit amet dolor imperdiet aliquam. Quisque nisi arcu, vehicula eget purus at, volutpat scelerisque mauris. In sed libero in arcu bibendum iaculis et quis sem. Vivamus tempor tincidunt urna, et euismod erat faucibus sit amet. Cras sed magna eu odio ultricies tempor. Morbi pulvinar eu nisl convallis pharetra. Morbi ac consequat orci. Curabitur scelerisque est non lobortis eleifend. Aenean quis tortor condimentum felis fringilla tristique. Vivamus imperdiet tincidunt elementum. Fusce hendrerit lorem ut ante lacinia tempus. Curabitur libero lacus, vehicula faucibus volutpat vitae, posuere sit amet dolor. Duis metus quam, maximus a tincidunt ac, ornare at turpis. Proin nec sagittis neque. Nullam rutrum, est nec luctus dignissim, mauris lacus varius augue, non vulputate nisi tellus quis purus.</p>'
        ];
    }

    /**
     * Set model factory
     *
     * @param array $data
     * @return void
     */
    public function blogPost($data = [])
    {
        return factory(Post::class)
        ->create($data)
        ->each(function ($post) {
            $post->categories()->save(factory(Category::class)->make());
            $post->tags()->createMany(
                factory(Tag::class, 3)->make()->toArray()
            );
        });
    }

    /**
     * Create blog categoru
     *
     * @return void
     */
    public function category()
    {
        return factory(Category::class)->create();
    }

    /**
     * Create blog tag
     *
     * @return void
     */
    public function tag()
    {
        return factory(Tag::class)->create();
    }

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreateBlogPost()
    {
        $this->blogPost($this->dummyContent());

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test updating a record
     *
     * @return void
     */
    public function testUpdateBlogPost()
    {
        $this->blogPost();

        $blogPost = Post::first();
        $blogPost->fill($this->dummyContent());
        $blogPost->save();

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeleteBlogPost()
    {
        $blogPost = $this->blogPost();

        $blogPost = Post::first();
        $blogPost->delete();

        $this->assertSoftDeleted($blogPost);

        $blogPost->forceDelete();

        $this->assertDeleted($blogPost);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedCreateBlogPostFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => __('The given data was invalid.'),
                'errors' => [
                    'title' => [__('The title field is required.')],
                    'content' => [__('The content field is required.')],
                    'image' => [__('The image field is required.')],
                    'blog_category_id' => [__('The category field is required.')],
                    'blog_tag_id' => [__('The tag field is required.')]
                ]
            ]);
    }

    /**
     * Test creating a record through API
     *
     * @return void
     */
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
            'title' => 'Sed eleifend pulvinar mauris id luctus',
            'content' => 'Suspendisse non semper lorem, et maximus dolor. Aenean interdum tincidunt vulputate. Integer porta augue at ante mollis feugiat. Integer non dignissim massa, sit amet convallis metus. Quisque porta vulputate scelerisque. Donec imperdiet faucibus tellus sit amet laoreet. Etiam malesuada, tellus sit amet accumsan mattis, elit dui fermentum ipsum, vel feugiat purus est eu sem. Etiam quis lacinia augue, non lacinia ante. Maecenas vel velit at lorem tempor aliquam eget nec ligula. Donec lacinia, nibh sit amet ultrices lacinia, metus ipsum maximus felis, a interdum ante purus at massa. Donec luctus ut elit sit amet congue. Maecenas auctor imperdiet condimentum. Cras ut hendrerit tellus. Integer semper turpis sed diam pellentesque tincidunt. Nunc at egestas erat. Fusce ac dapibus magna, ut accumsan enim. Nullam quis placerat nulla. Mauris sollicitudin massa ac bibendum malesuada. Fusce tempor cursus rhoncus.',
            'image' => $thumbnail,
            'blog_category_id' => $blogCategory,
            'blog_tag_id' => $blogTag
        ]);

        Storage::disk('local')->assertExists('/public/blog/' . $response['data']['image']);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully created.'),
                'data' => [
                    'title' => 'Sed eleifend pulvinar mauris id luctus',
                    'slug' => Str::slug('Sed eleifend pulvinar mauris id luctus', '-'),
                    'content' => 'Suspendisse non semper lorem, et maximus dolor. Aenean interdum tincidunt vulputate. Integer porta augue at ante mollis feugiat. Integer non dignissim massa, sit amet convallis metus. Quisque porta vulputate scelerisque. Donec imperdiet faucibus tellus sit amet laoreet. Etiam malesuada, tellus sit amet accumsan mattis, elit dui fermentum ipsum, vel feugiat purus est eu sem. Etiam quis lacinia augue, non lacinia ante. Maecenas vel velit at lorem tempor aliquam eget nec ligula. Donec lacinia, nibh sit amet ultrices lacinia, metus ipsum maximus felis, a interdum ante purus at massa. Donec luctus ut elit sit amet congue. Maecenas auctor imperdiet condimentum. Cras ut hendrerit tellus. Integer semper turpis sed diam pellentesque tincidunt. Nunc at egestas erat. Fusce ac dapibus magna, ut accumsan enim. Nullam quis placerat nulla. Mauris sollicitudin massa ac bibendum malesuada. Fusce tempor cursus rhoncus.',
                ]
            ]);

        $response = $this->postJson($this->url, array_merge($this->dummyContent(), [
            'image' => $thumbnail,
            'blog_category_id' => $blogCategory,
            'blog_tag_id' => $blogTag
        ]));

        Storage::disk('local')->assertExists('/public/blog/' . $response['data']['image']);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully created.'),
                'data' => $this->dummyContent()
            ]);

        $response = $this->postJson($this->url, array_merge($this->dummyContent(), [
            'image' => $thumbnail,
            'blog_category_id' => $blogCategory,
            'blog_tag_id' => $blogTag
        ]));

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
    public function testFailedReadBlogPostFromApi()
    {
        (new Auth())->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson($this->url . '/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\Blog\Post] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing record through API
     *
     * @return void
     */
    public function testSuccessfullReadBlogPostFromApi()
    {
        (new Auth())->createAuth();

        $this->blogPost($this->dummyContent());
        $blogPost = Post::first();

        $response = $this->getJson($this->url . '/' . $blogPost->id);

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
    public function testDeleteBlogPostFromApi()
    {
        (new Auth())->createAuth();

        $this->blogPost();
        $blogPost = Post::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$blogPost->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertSoftDeleted($blogPost);
    }

    /**
     * Test restore deleted record
     *
     * @return void
     */
    public function testRestoreDeletedBlogPostFromApi()
    {
        (new Auth())->createAuth();

        $this->blogPost($this->dummyContent());
        $blogPost = Post::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$blogPost->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertSoftDeleted($blogPost);

        $response = $this->postJson($this->url . '/restore', [
            'selectedData' => [$blogPost->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully restored.')
            ]);
        $this->assertDatabaseCount($this->table, 1);
    }

    /**
     * Test permanent delete a record
     *
     * @return void
     */
    public function testPermanentDeleteBlogPostFromApi()
    {
        (new Auth())->createAuth();

        $this->blogPost();
        $blogPost = Post::first();

        $response = $this->deleteJson($this->url . '/delete', [
            'selectedData' => [$blogPost->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertDeleted($blogPost);
    }
}
