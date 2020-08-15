<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTag;
use App\Http\Resources\Tags as TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = Tag::when($request->filled('search'), function ($search) use ($request)
                    {
                        $search->where('name', 'like', "%{$request->search}%");
                    })->get();
        $data = collect(TagResource::collection($tags));
        return $this->successResponse($data);
    }

    /**
     * Display a listing of the resource for datatable
     *
     * @param Request $request
     * @return void
     */
    public function list(Request $request)
    {
        $data = Tag::orderBy($request->sort_field, $request->sort_order)
                    ->select('id', 'name', 'slug', 'created_at')
                    ->withCount('works')
                    ->paginate($request->per_page);
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTag $request)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $tag = Tag::create($request->all());
        $data = collect(new TagResource($tag));
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
        $data = collect(new TagResource($tag));
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTag $request, Tag $tag)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $tag->fill($request->all());
        $tag->save();
        $data = collect(new TagResource($tag));
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
