<?php

namespace App\Http\Controllers\API\Portfolio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio\Category;
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
                    ->select('id', 'name', 'slug', 'created_at')
                    ->withCount('works')
                    ->paginate($request->per_page);
        return $this->successResponse($data);
    }
}
