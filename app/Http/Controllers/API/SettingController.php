<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSetting;
use App\Http\Resources\Settings as SettingResource;
use App\Setting;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(SettingResource::collection(Setting::all()));
        return ResponseBuilder::success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSetting $request)
    {
        $setting = Setting::create($request->all());
        $data = collect(new SettingResource($setting));
        return ResponseBuilder::success($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        $data = collect(new SettingResource($setting));
        return ResponseBuilder::success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSetting $request, Setting $setting)
    {
        $setting->fill($request->all());
        $setting->save();
        $data = collect(new SettingResource($setting));
        return ResponseBuilder::success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
