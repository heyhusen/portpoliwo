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
        Setting::get()->each(function ($item) use ($request)
        {
            Setting::where('name', $item->name)->update(['value' => $request->{$item->name}]);
        });
        $data = collect(SettingResource::collection(Setting::get()));
        return $this->dataCreated($data);
    }
}
