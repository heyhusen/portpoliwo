<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ListAccount extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = User::orderBy($request->sort_field, $request->sort_order)
                    ->select('id', 'name', 'email', 'photo', 'created_at')
                    ->paginate($request->per_page);
        return $this->successResponse($data);
    }
}
