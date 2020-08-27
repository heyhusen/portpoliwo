<?php

namespace App\Http\Controllers\API\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Page;
use Illuminate\Http\Request;

class ListPage extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = Page::orderBy($request->sort_field, $request->sort_order)
                    ->select('id', 'title', 'slug', 'content', 'created_at')
                    ->paginate($request->per_page);
        return $this->successResponse($data);
    }
}
