<?php

namespace App\Http\Controllers\API\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StorePage;
use App\Http\Resources\Blog\Pages;
use App\Models\Blog\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(Pages::collection(Page::get()));
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePage $request)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $page = Page::create($request->all());
        if ($request->hasFile('image')) {
            $page
                ->addMedia($request->file('image'))
                ->toMediaCollection('thumbnail');
        }
        $data = collect(new Pages($page));
        return $this->dataCreated($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        $data = collect(new Pages($page));
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(StorePage $request, Page $page)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $page->fill($request->all());
        $page->save();
        if ($request->hasFile('image')) {
            $page
                ->addMedia($request->file('image'))
                ->toMediaCollection('thumbnail');
        }
        $data = collect(new Pages($page));
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
        Page::destroy($request->selectedData);
        return $this->dataDeleted();
    }
}
