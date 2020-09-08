<?php

namespace App\Http\Controllers\API\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\Pages;
use App\Models\Blog\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

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
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'unique:blog_pages,title'],
            'slug' => ['unique:blog_pages,slug'],
            'content' => ['required'],
            'image' => ['required', 'image'],
        ]);
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $page = Page::create($request->all());
        $page->image = $this->uploadImage($request, $page);
        $page->save();
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
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => ['required', Rule::unique('blog_pages')->ignore($page)],
            'slug' => [Rule::unique('blog_pages')->ignore($page)],
            'content' => ['required'],
            'image' => ['image'],
        ]);
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->title]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->title]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $page->fill($request->all());
        $page->image = $this->uploadImage($request, $page);
        $page->save();
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

    /**
     * Upload image
     *
     * @param Request $request
     * @param Page $model
     * @param string $name
     * @return void
     */
    public function uploadImage(Request $request, Page $model, $name = 'default.jpg')
    {
        if ($request->hasFile('image')) {
            Image::load($request->image)
                ->fit(Manipulations::FIT_CROP, 1280, 720)
                ->save();
            $request->image->store('public/blog/page/' . $model->id);
            $name = $model->id . '/' . $request->image->hashName() . $request->image->extension();
        }
        return $name;
    }
}
