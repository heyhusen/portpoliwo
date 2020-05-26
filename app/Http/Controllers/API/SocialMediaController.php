<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSocialMedia;
use App\Http\Resources\SocialMedias as SocialMediaResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(SocialMediaResource::collection(SocialMedia::all()));
        return ResponseBuilder::success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSocialMedia $request)
    {
        $socialMedia = SocialMedia::create($request->all());
        $data = collect(new SocialMediaResource($socialMedia));
        return ResponseBuilder::success($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SocialMedia  $socialMedia
     * @return \Illuminate\Http\Response
     */
    public function show(SocialMedia $socialMedia)
    {
        $data = collect(new SocialMediaResource($socialMedia));
        return ResponseBuilder::success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SocialMedia  $socialMedia
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSocialMedia $request, SocialMedia $socialMedia)
    {
        $socialMedia->fill($request->all());
        $socialMedia->save();
        $data = collect(new SocialMediaResource($socialMedia));
        return ResponseBuilder::success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SocialMedia  $socialMedia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        SocialMedia::destroy($request->selectedData);
        return ResponseBuilder::success();
    }
}
