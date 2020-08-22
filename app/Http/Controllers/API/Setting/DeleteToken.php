<?php

namespace App\Http\Controllers\API\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteToken extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $request->user('sanctum')->tokens()->where('id', $id)->delete();
        return $this->dataDeleted();
    }
}
