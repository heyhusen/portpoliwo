<?php

namespace App\Http\Controllers\API\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\Posts;
use App\Models\Blog\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(Posts::collection(Post::get()));
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required', 'unique:blog_posts,title'],
            'slug' => ['unique:blog_posts,slug'],
            'content' => ['required'],
            'image' => ['required', 'image'],
            'blog_category_id' => ['required', 'array', 'min:1'],
            'blog_category_id.*' => ['required', 'min:1'],
            'blog_tag_id' => ['required', 'array', 'min:1'],
            'blog_tag_id.*' => ['required', 'min:1'],
        ], [], [
            'blog_category_id' => 'category',
            'blog_tag_id' => 'tag'
        ])->validate();
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $post = Post::create($request->all());
        $post->image = $this->uploadImage($request, $post);
        $post->save();
        $post->categories()->sync($request->blog_category_id);
        $post->tags()->sync($request->blog_tag_id);
        $data = collect(new Posts($post));
        return $this->dataCreated($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $data = collect(new Posts($post));
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        Validator::make($request->all(), [
            'title' => ['required', Rule::unique('blog_posts')->ignore($post)],
            'slug' => [Rule::unique('blog_posts')->ignore($post)],
            'content' => ['required'],
            'image' => ['image'],
            'blog_category_id' => ['required', 'array', 'min:1'],
            'blog_category_id.*' => ['required', 'min:1'],
            'blog_tag_id' => ['required', 'array', 'min:1'],
            'blog_tag_id.*' => ['required', 'min:1'],
        ], [], [
            'blog_category_id' => 'category',
            'blog_tag_id' => 'tag'
        ])->validate();
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $post->fill($request->all());
        $post->image = $this->uploadImage($request, $post);
        $post->save();
        $post->categories()->sync($request->blog_category_id);
        $post->tags()->sync($request->blog_tag_id);
        $data = collect(new Posts($post));
        return $this->dataUpdated($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Post::destroy($request->selectedData);
        return $this->dataDeleted();
    }

    /**
     * Upload image
     *
     * @param Request $request
     * @param Post $model
     * @param string $name
     * @return void
     */
    public function uploadImage(Request $request, Post $model, $name = 'default.jpg')
    {
        if ($request->hasFile('image')) {
            Image::load($request->image)
                ->fit(Manipulations::FIT_CROP, 1280, 720)
                ->save();
            $request->image->store('public/blog/' . $model->id);
            $name = $model->id . '/' . $request->image->hashName();
        }
        return $name;
    }
}
