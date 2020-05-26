<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Work;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWork;
use App\Http\Resources\Works as WorkResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(WorkResource::collection(Work::all()));
        return ResponseBuilder::success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWork $request)
    {
        $work = Work::create($request->all());
        $work->category()->attach($request->category_id);
        $work->tags()->attach($request->tag_id);
        $data = collect(new WorkResource($work));
        return ResponseBuilder::success($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        $data = collect(new WorkResource($work));
        return ResponseBuilder::success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWork $request, Work $work)
    {
        $work->fill($request->all());
        $work->save();
        $work->category()->sync($request->category_id);
        $work->tags()->sync($request->tag_id);
        $data = collect(new WorkResource($work));
        return ResponseBuilder::success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Work::destroy($request->selectedData);
        return ResponseBuilder::success();
    }
}
