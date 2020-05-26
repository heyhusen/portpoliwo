<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTag;
use App\Http\Resources\Tags as TagResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(TagResource::collection(Tag::all()));
        return ResponseBuilder::success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTag $request)
    {
        if ( ! $request->filled('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $tag = Tag::create($request->all());
        $data = collect(new TagResource($tag));
        return ResponseBuilder::success($data);
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
        return ResponseBuilder::success($data);
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
        if ( ! $request->filled('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $tag->fill($request->all());
        $tag->save();
        $data = collect(new TagResource($tag));
        return ResponseBuilder::success($data);
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
        return ResponseBuilder::success();
    }
}
