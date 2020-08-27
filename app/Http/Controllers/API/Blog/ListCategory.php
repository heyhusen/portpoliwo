<?php

namespace App\Http\Controllers\API\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use Illuminate\Http\Request;

class ListCategory extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = Category::orderBy($request->sort_field, $request->sort_order)
                    ->select('id', 'title', 'slug', 'description', 'created_at')
                    ->withCount('posts')
                    ->paginate($request->per_page);
        return $this->successResponse($data);
    }
}
