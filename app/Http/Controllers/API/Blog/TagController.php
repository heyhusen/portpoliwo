<?php

namespace App\Http\Controllers\API\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\Tags;
use App\Models\Blog\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Tag::when($request->filled('search'), function ($search) use ($request) {
                            $search->where('title', 'like', "%{$request->search}%");
        })->get();
        $data = collect(Tags::collection($categories));
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
        $request->validate([
            'title' => ['required', 'unique:blog_tags,title'],
            'slug' => ['unique:blog_tags,slug']
        ]);
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $tag = Tag::create($request->all());
        $data = collect(new Tags($tag));
        return $this->dataCreated($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $data = collect(new Tags($tag));
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'title' => ['required', Rule::unique('blog_tags')->ignore($tag)],
            'slug' => [Rule::unique('blog_tags')->ignore($tag)]
        ]);
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $tag->fill($request->all());
        $tag->save();
        $data = collect(new Tags($tag));
        return $this->dataUpdated($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Tag::destroy($request->selectedData);
        return $this->dataDeleted();
    }
}
