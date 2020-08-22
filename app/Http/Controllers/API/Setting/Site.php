<?php

namespace App\Http\Controllers\API\Setting;

use App\Http\Controllers\Controller;
use App\Http\Resources\Settings as SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class Site extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = collect(SettingResource::collection(Setting::all()));
        return $this->successResponse($data);
    }
}
