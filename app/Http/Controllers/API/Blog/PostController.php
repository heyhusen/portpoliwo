<?php

namespace App\Http\Controllers\API\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StorePost;
use App\Http\Resources\Blog\Posts;
use App\Models\Blog\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    public function store(StorePost $request)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $post = Post::create($request->all());
        if ($request->hasFile('image')) {
            $post
                ->addMedia($request->file('image'))
                ->toMediaCollection('thumbnail');
        }
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
    public function update(StorePost $request, Post $post)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $post->fill($request->all());
        $post->save();
        if ($request->hasFile('image')) {
            $post
                ->addMedia($request->file('image'))
                ->toMediaCollection('thumbnail');
        }
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
}
