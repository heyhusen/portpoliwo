<?php

namespace App\Http\Controllers\API\Portfolio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\StoreWork;
use App\Http\Resources\Portfolio\Works;
use App\Models\Portfolio\Work;
use Illuminate\Http\Request;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

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
        $work->photo = $this->uploadPhoto($request, $work);
        $work->save();
        $work->categories()->sync($request->category_id);
        $work->tags()->sync($request->tag_id);
        $data = collect(new Works($work));
        return $this->dataUpdated($data);
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

    /**
     * Upload photo
     *
     * @param Request $request
     * @param Post $model
     * @param string $name
     * @return void
     */
    public function uploadPhoto(Request $request, Work $model, $name = 'default.jpg')
    {
        if ($request->hasFile('photo')) {
            Image::load($request->photo)
                ->fit(Manipulations::FIT_CROP, 1280, 720)
                ->save();
            $request->photo->store('public/portfolio/' . $model->id);
            $name = $model->id . '/' . $request->photo->hashName() . $request->photo->extension();
        }
        return $name;
    }
}
