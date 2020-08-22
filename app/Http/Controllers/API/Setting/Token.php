<?php

namespace App\Http\Controllers\API\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Token extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return $this->successResponse($request->user('sanctum')->tokens);
    }
}
