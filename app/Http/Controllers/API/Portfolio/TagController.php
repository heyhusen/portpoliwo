<?php

namespace App\Http\Controllers\API\Portfolio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\StoreTag;
use App\Http\Resources\Portfolio\Tags;
use App\Models\Portfolio\Tag;
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
        $tags = Tag::when($request->filled('search'), function ($search) use ($request) {
                        $search->where('name', 'like', "%{$request->search}%");
        })->get();
        $data = collect(Tags::collection($tags));
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
            'name' => ['required', 'unique:portfolio_tags,name'],
            'slug' => ['unique:portfolio_tags,slug']
        ]);
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $tag = Tag::create($request->all());
        $data = collect(new Tags($tag));
        return $this->dataCreated($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
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
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => ['required', Rule::unique('portfolio_tags')->ignore($tag)],
            'slug' => [Rule::unique('portfolio_tags')->ignore($tag)]
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
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Tag::destroy($request->selectedData);
        return $this->dataDeleted();
    }
}
