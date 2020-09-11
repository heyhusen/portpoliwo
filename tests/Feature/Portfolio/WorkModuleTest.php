<?php

namespace Tests\Feature\Portfolio;

use App\Models\Portfolio\Category;
use App\Models\Portfolio\Tag;
use App\Models\Portfolio\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    /**
     * Set dummy ontent
     *
     * @return void
     */
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

    /**
     * Set model factory
     *
     * @param array $data
     * @return void
     */
    public function createPortfolioWork($data = [])
    {
        return factory(Work::class)
        ->create($data)
        ->each(function ($work) {
            $work->categories()->save(factory(Category::class)->make());
            $work->tags()->createMany(
                factory(Tag::class, 3)->make()->toArray()
            );
        });
    }

    /**
     * Create portfolio categoru
     *
     * @return void
     */
    public function createCategory()
    {
        return factory(Category::class)->create();
    }

    /**
     * Create portfolio tag
     *
     * @return void
     */
    public function createTag()
    {
        return factory(Tag::class)->create();
    }

    /**
     * Test creating a record
     *
     * @return void
     */
    public function testCreatePortfolioWork()
    {
        $this->createPortfolioWork($this->dummyContent());

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test updating a record
     *
     * @return void
     */
    public function testUpdatePortfolioWork()
    {
        $this->createPortfolioWork();

        $portfolioWork = Work::first();
        $portfolioWork->fill($this->dummyContent());
        $portfolioWork->save();

        $this
            ->assertDatabaseCount($this->table, 1)
            ->assertDatabaseHas($this->table, $this->dummyContent());
    }

    /**
     * Test deleting a record
     *
     * @return void
     */
    public function testDeletePortfolioWork()
    {
        $portfolioWork = $this->createPortfolioWork();

        $portfolioWork = Work::first();
        $portfolioWork->delete();

        $this->assertSoftDeleted($portfolioWork);

        $portfolioWork->forceDelete();

        $this->assertDeleted($portfolioWork);
    }

    /**
     * Test creating a record through API with validation
     *
     * @return void
     */
    public function testFailedCreatePortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $response = $this->postJson($this->url);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => __('The given data was invalid.'),
                'errors' => [
                    'name' => [__('The name field is required.')],
                    'description' => [__('The description field is required.')],
                    'portfolio_category_id' => [__('The category field is required.')],
                    'portfolio_tag_id' => [__('The tag field is required.')]
                ]
            ]);
    }

    /**
     * Test creating a record through API
     *
     * @return void
     */
    public function testSuccessfullCreatePortfolioWorkFromApi()
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
            'portfolio_category_id' => $portfolioCategory,
            'portfolio_tag_id' => $portfolioTag
        ]));

        Storage::disk('local')->assertExists('/public/portfolio/' . $response['data']['photo']);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully created.'),
                'data' => $this->dummyContent()
            ]);

        $response = $this->postJson($this->url, array_merge($this->dummyContent(), [
            'photo' => $photo,
            'portfolio_category_id' => $portfolioCategory,
            'portfolio_tag_id' => $portfolioTag
        ]));

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => __('The given data was invalid.'),
                'errors' => [
                    'name' => [__('The name has already been taken.')]
                ]
            ]);
    }

    /**
     * Test failed reading an existing record through API
     *
     * @return void
     */
    public function testFailedReadPortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $uuid = Str::uuid();

        $response = $this->getJson($this->url . '/' . $uuid);

        $response
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No query results for model [App\\Models\\Portfolio\Work] ' . $uuid
            ]);
    }

    /**
     * Test reading an existing record through API
     *
     * @return void
     */
    public function testSuccessfullReadPortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $this->createPortfolioWork($this->dummyContent());
        $portfolioWork = Work::first();

        $response = $this->getJson($this->url . '/' . $portfolioWork->id);

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
    public function testFailedUpdatePortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $this->createPortfolioWork();
        $portfolioWork = Work::first();

        $response = $this->putJson($this->url . '/' . $portfolioWork->id, [
            'name' => '',
            'description' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => __('The given data was invalid.'),
                'errors' => [
                    'name' => [__('The name field is required.')],
                    'description' => [__('The description field is required.')]
                ]
            ]);
    }

    /**
     * Test successfully updating an existing record through API
     *
     * @return void
     */
    public function testSuccessfullyUpdatePortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $this->createPortfolioWork();
        $portfolioWork = Work::first();

        $this->createCategory();
        $portfolioCategory = Category::pluck('id');

        $this->createTag();
        $portfolioTag = Tag::pluck('id');

        Storage::fake('local');
        $photo = UploadedFile::fake()->image('photo.png');

        $response = $this->putJson($this->url . '/' . $portfolioWork->id, array_merge($this->dummyContent(), [
            'photo' => $photo,
            'portfolio_category_id' => $portfolioCategory,
            'portfolio_tag_id' => $portfolioTag
        ]));

        Storage::disk('local')->assertExists('/public/portfolio/' . $response['data']['photo']);

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
    public function testDeletePortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $this->createPortfolioWork();
        $portfolioWork = Work::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$portfolioWork->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertSoftDeleted($portfolioWork);
    }

    /**
     * Test restore deleted record
     *
     * @return void
     */
    public function testRestoreDeletedPortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $this->createPortfolioWork($this->dummyContent());
        $portfolioWork = Work::first();

        $response = $this->deleteJson($this->url, [
            'selectedData' => [$portfolioWork->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertSoftDeleted($portfolioWork);

        $response = $this->postJson($this->url . '/restore', [
            'selectedData' => [$portfolioWork->id]
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
    public function testPermanentDeletePortfolioWorkFromApi()
    {
        (new Auth())->createAuth();

        $this->createPortfolioWork();
        $portfolioWork = Work::first();

        $response = $this->deleteJson($this->url . '/delete', [
            'selectedData' => [$portfolioWork->id]
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => __('Data successfully deleted.')
            ]);

        $this->assertDeleted($portfolioWork);
    }
}
