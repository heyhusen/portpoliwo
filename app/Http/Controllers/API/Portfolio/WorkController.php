<?php

namespace App\Http\Controllers\API\Portfolio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\StoreWork;
use App\Http\Resources\Portfolio\Works;
use App\Models\Portfolio\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(Works::collection(Work::all()));
        return $this->successResponse($data);
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
        $work->photo = $this->uploadPhoto($request, $work);
        $work->save();
        $work->categories()->attach($request->category_id);
        $work->tags()->attach($request->tag_id);
        $data = collect(new Works($work));
        return $this->dataCreated($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        $data = collect(new Works($work));
        return $this->successResponse($data);
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
        $work->photo = $this->uploadPhoto($request, $work, $work->photo);
        $work->save();
        $work->categories()->sync($request->category_id);
        $work->tags()->sync($request->tag_id);
        $data = collect(new Works($work));
        return $this->dataUpdated($data);
    }

    /**
     * Upload photo
     *
     * @param Request $request
     * @param string $path
     * @return void
     */
    public function uploadPhoto(Request $request, $data, $name = null)
    {
        if ($request->hasFile('photo')) {
            $name = $data->id . '/' . $request->photo->hashName();
            $path = $request->photo->store('public/work/' . $data->id);
        }
        return $name;
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
        return $this->dataDeleted();
    }
}
