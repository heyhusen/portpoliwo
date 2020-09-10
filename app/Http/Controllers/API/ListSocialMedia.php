<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListSocialMedia extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = DB::table('social_medias')
                    ->orderBy($request->sort_field, $request->sort_order)
                    ->select('id', 'name', 'icon', 'url', 'created_at')
                    ->paginate($request->per_page);
        return $this->successResponse($data);
    }
}
