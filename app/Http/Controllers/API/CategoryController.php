<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategory;
use App\Http\Resources\Categories as CategoryResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(CategoryResource::collection(Category::all()));
        return ResponseBuilder::success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategory $request)
    {
        if ( ! $request->filled('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $category = Category::create($request->all());
        $data = collect(new CategoryResource($category));
        return ResponseBuilder::success($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $data = collect(new CategoryResource($category));
        return ResponseBuilder::success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategory $request, Category $category)
    {
        if ( ! $request->filled('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $category->fill($request->all());
        $category->save();
        $data = collect(new CategoryResource($category));
        return ResponseBuilder::success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Category::destroy($request->selectedData);
        return ResponseBuilder::success();
    }
}
