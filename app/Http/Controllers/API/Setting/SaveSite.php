<?php

namespace App\Http\Controllers\API\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSetting;
use App\Http\Resources\Settings as SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SaveSite extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreSetting $request)
    {
        for ($i=0; $i < count($request->name); $i++) {
            Setting::where('name', $request->name[$i])
                ->update(['value' => $request->value[$i]]);
        }
        $settings = Setting::whereIn('name', $request->name)->get();
        $data = collect(SettingResource::collection($settings));
        return $this->dataCreated($data);
    }
}
