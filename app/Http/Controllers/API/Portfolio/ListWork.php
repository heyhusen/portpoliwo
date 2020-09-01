<?php

namespace App\Http\Controllers\API\Portfolio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio\Work;
use Illuminate\Http\Request;

class ListWork extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = Work::orderBy($request->sort_field, $request->sort_order)
                    ->select('id', 'name', 'description', 'url', 'photo', 'created_at')
                    ->paginate($request->per_page);
        return $this->successResponse($data);
    }
}
