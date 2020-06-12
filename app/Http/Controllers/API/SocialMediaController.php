<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSocialMedia;
use App\Http\Resources\SocialMedias as SocialMediaResource;
use App\SocialMedia;
use Illuminate\Http\Request;

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
        return $this->successResponse($data);
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
        return $this->dataCreated($data);
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
        return $this->successResponse($data);
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
        return $this->dataUpdated($data);
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
        return $this->dataDeleted();
    }
}
