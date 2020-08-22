<?php

namespace App\Http\Controllers\API\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateToken extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        return $this->dataCreated([
            'token' => $request->user('sanctum')->createToken($request->name)->plainTextToken
        ]);
    }
}
