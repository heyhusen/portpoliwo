<?php

namespace Tests\Feature\Blog;

use App\Models\Blog\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Feature\Auth;
use Tests\TestCase;

class PageModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $table = 'blog_pages';
    protected $url = '/api/blog/page';

    /**
     * Set dummy ontent
     *
     * @return void
     */
    public function dummyContent()
    {
        return [
            'title' => 'Curabitur ornare massa felis, in luctus nunc tempus et',
            'slug' => Str::slug('Curabitur ornare massa felis, in luctus nunc tempus et', '-'),
            'content' => '<p>Curabitur vitae erat varius, lobortis turpis non, tincidunt lacus.
            Cras mollis tristique mattis. Nullam varius nisi turpis, et sagittis est consequat a.
            Curabitur aliquam, leo ac tristique pharetra, enim risus pellentesque ex,
            placerat tincidunt ante sapien sit amet libero. Proin a elementum mauris, vel cursus dolor.
            Etiam non quam at libero interdum suscipit sed nec urna. Proin ultrices ligula diam,
            sit amet interdum tortor volutpat id. Praesent vitae sem nec dui finibus lobortis vel at leo.
            Nulla risus arcu, fermentum non turpis ac, luctus iaculis nisi. Nullam vel consectetur eros.
            Cras sodales sit amet est sit amet pharetra. Praesent ultricies sem sed urna ullamcorper luctus.
            Aenean cursus arcu faucibus, ullamcorper leo a, volutpat diam.</p><p>Quisque finibus velit in nisl cursus,
            et dictum ligula efficitur. Integer euismod venenatis nisi, in efficitur sapien ultrices et.
            Sed sem magna, pretium vel tempor at, pretium nec justo. Donec dignissim lorem magna,
            convallis ullamcorper mi tincidunt eget. Integer sodales erat sed maximus bibendum.
            Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
            Fusce accumsan urna in nisi maximus lobortis. Pellentesque ut auctor ligula.
            Morbi ullamcorper elit at mi tempor, et ultrices elit finibus. Maecenas in bibendum diam,
            id scelerisque dolor. Cras dignissim efficitur varius. Curabitur rutrum vulputate felis ac congue.
            Aenean placerat dui venenatis, porttitor purus eget, egestas ex. Integer lectus mauris,
            tincidunt in vehicula eget, gravida vitae lectus. Suspendisse eget eros turpis.
            Nullam eget sodales est, vel aliquet nisi.</p><p>Phasellus vitae convallis massa.
            Sed ex ipsum, maximus eu turpis pretium, convallis vehicula lorem. Lorem ipsum dolor sit amet,
            consectetur adipiscing elit. Mauris consectetur, felis a dapibus dapibus, purus ligula suscipit diam,
            at ultricies est enim ut nibh. Sed vitae arcu sapien. Integer efficitur leo sed nulla sagittis feugiat.
            Ut justo tortor, hendrerit nec elit ut, lobortis eleifend velit. Quisque pretium pretium elit,
            quis luctus nulla aliquet ac. In tristique consectetur iaculis. Donec non pulvinar velit,
            eu condimentum felis. Donec facilisis lorem id felis tincidunt eleifend.
            Praesent ultrices tellus eu commodo porta. Nullam nec volutpat turpis. Ut lacus felis,
            feugiat non turpis et, efficitur bibendum tellus. Donec fringilla mi et sapien tristique,
            in rutrum elit accumsan.</p><p>In porta congue elit nec malesuada.
            Phasellus vestibulum urna eget ligula eleifend pharetra. Vestibulum ultrices sapien enim,
            ac efficitur metus accumsan vitae. Aliquam quis interdum risus. Donec varius, velit vel luctus auctor,
            lorem diam eleifend ipsum, a malesuada metus diam non turpis. Quisque hendrerit pellentesque quam,
            et malesuada risus facilisis non. Suspendisse nec arcu vel nulla eleifend gravida ut eu nunc.
            Maecenas nec mollis mi, at aliquet risus. Proin in luctus nisi. Nulla porta lacinia tellus vitae euismod.
            Suspendisse sed quam erat. Maecenas at ornare odio, quis aliquet mauris. Sed egestas,
            orci rutrum molestie rutrum, velit tellus ultricies elit, in ullamcorper ante dolor in sapien.
            Proin aliquam, purus nec scelerisque tristique, diam purus ullamcorper purus,
            condimentum euismod urna lectus ut ante.</p>'
        ];
    }

    /**
     * Set model factory
     *
     * @param array $data
     * @return void
     */
    public function blogPage($data = [])
    {
        return factory(Page::class)->create($data);
    }

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreateBlogPost()
    {
        $this->blogPage($this->dummyContent());

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
        $this->blogPage();

        $blogPage = Page::first();
        $blogPage->fill($this->dummyContent());
        $blogPage->save();

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
        $blogPage = $this->blogPage();

        $blogPage = Page::first();
        $blogPage->delete();

        $this->assertSoftDeleted($blogPage);

        $blogPage->forceDelete();

        $this->assertDeleted($blogPage);
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
                    'content' => [__('The content field is required.')]
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

        Storage::fake('local');
        $thumbnail = UploadedFile::fake()->image('thumbnail.png');

        $content = 'Curabitur vitae erat varius, lobortis turpis non, tincidunt lacus.
        Cras mollis tristique mattis. Nullam varius nisi turpis, et sagittis est consequat a.
        Curabitur aliquam, leo ac tristique pharetra, enim risus pellentesque ex,
        placerat tincidunt ante sapien sit amet libero. Proin a elementum mauris, vel cursus dolor.
        Etiam non quam at libero interdum suscipit sed nec urna. Proin ultrices ligula diam,
        sit amet interdum tortor volutpat id. Praesent vitae sem nec dui finibus lobortis vel at leo.
        Nulla risus arcu, fermentum non turpis ac, luctus iaculis nisi. Nullam vel consectetur eros.
        Cras sodales sit amet est sit amet pharetra. Praesent ultricies sem sed urna ullamcorper luctus.
        Aenean cursus arcu faucibus, ullamcorper leo a, volutpat diam.';

        $response = $this->postJson($this->url, [
            'title' => 'Vivamus a rhoncus ipsum, rutrum laoreet libero',
            'content' => $content,
            'image' => $thumbnail
        ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully created.'),
                'data' => [
                    'title' => 'Vivamus a rhoncus ipsum, rutrum laoreet libero',
                    'slug' => Str::slug('Vivamus a rhoncus ipsum, rutrum laoreet libero', '-'),
                    'content' => $content,
                ]
            ]);

        $response = $this->postJson($this->url, array_merge($this->dummyContent(), [
            'image' => $thumbnail
        ]));

        Storage::disk('local')->assertExists('/public/blog/page/' . $response['data']['image']);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully created.'),
                'data' => $this->dummyContent()
            ]);

        $response = $this->postJson($this->url, array_merge($this->dummyContent(), [
            'image' => $thumbnail
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
                'message' => 'No query results for model [App\\Models\\Blog\Page] ' . $uuid
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

        $this->blogPage($this->dummyContent());
        $blogPage = Page::first();

        $response = $this->getJson($this->url . '/' . $blogPage->id);

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

        $this->blogPage();
        $blogPage = Page::first();

        $response = $this->putJson($this->url . '/' . $blogPage->id, [
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

        $this->blogPage();
        $blogPage = Page::first();

        Storage::fake('local');
        $thumbnail = UploadedFile::fake()->image('thumbnail.png');

        $response = $this->putJson($this->url . '/' . $blogPage->id, array_merge($this->dummyContent(), [
            'image' => $thumbnail,
        ]));

        Storage::disk('local')->assertExists('/public/blog/page/' . $response['data']['image']);

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

        $this->blogPage();
        $blogPage = Page::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$blogPage->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertSoftDeleted($blogPage);
    }

    /**
     * Test restore deleted record
     *
     * @return void
     */
    public function testRestoreDeletedBlogPostFromApi()
    {
        (new Auth())->createAuth();

        $this->blogPage($this->dummyContent());
        $blogPage = Page::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$blogPage->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertSoftDeleted($blogPage);

        $response = $this->postJson($this->url . '/restore', [
            'selectedData' => [$blogPage->id]
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

        $this->blogPage();
        $blogPage = Page::first();

        $response = $this->deleteJson($this->url . '/delete', [
            'selectedData' => [$blogPage->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertDeleted($blogPage);
    }
}
