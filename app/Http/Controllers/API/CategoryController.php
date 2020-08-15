<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use App\Http\Resources\Categories as CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::when($request->filled('search'), function ($search) use ($request)
                        {
                            $search->where('name', 'like', "%{$request->search}%");
                        })->get();
        $data = collect(CategoryResource::collection($categories));
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
        $data = Category::orderBy($request->sort_field, $request->sort_order)
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
    public function store(StoreCategory $request)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $category = Category::create($request->all());
        $data = collect(new CategoryResource($category));
        return $this->dataCreated($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $data = collect(new CategoryResource($category));
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategory $request, Category $category)
    {
        if ($request->missing('slug')) {
            $request->request->add(['slug' => $request->name]);
        }
        if ($request->has('slug') && empty($request->slug)) {
            $request->merge(['slug' => $request->name]);
        }
        $request->merge(['slug' => Str::slug($request->slug, '-')]);
        $category->fill($request->all());
        $category->save();
        $data = collect(new CategoryResource($category));
        return $this->dataUpdated($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Category::destroy($request->selectedData);
        return $this->dataDeleted();
    }
}
