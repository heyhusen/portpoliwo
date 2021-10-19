<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Resources\Users as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(UserResource::collection(User::all()));
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $request->merge(['password' => Hash::make($request->password)]);
        $user = User::create($request->all());
        $user->photo = $this->uploadPhoto($request, $user);
        $user->save();
        $data = collect(new UserResource($user));
        return $this->dataCreated($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $data = collect(new UserResource($user));
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUser $request, User $user)
    {
        if ($request->filled('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        if ($request->has('password') && empty($request->password)) {
            $request->merge(['password' => $user->password]);
        }
        $user->fill($request->all());
        $user->photo = $this->uploadPhoto($request, $user, $user->photo);
        $user->save();
        $data = collect(new UserResource($user));
        return $this->dataUpdated($data);
    }

    /**
     * Upload photo
     *
     * @param array $request
     * @param array $data
     * @param string $name
     * @return void
     */
    public function uploadPhoto(Request $request, User $model, $name = 'default.png')
    {
        if ($request->hasFile('photo')) {
            Image::load($request->photo)
                ->fit(Manipulations::FIT_CROP, 256, 256)
                ->save();
            $request->photo->store('public/avatar/' . $model->id);
            if (Storage::exists('public/avatar/'.$model->photo)) {
                Storage::delete('public/avatar/'.$model->photo);
            }
            $name = $model->id . '/' . $request->photo->hashName();
        }
        return $name;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->merge([
            'selectedData' => array_diff($request->selectedData, [$request->user('sanctum')->id])
        ]);
        User::destroy($request->selectedData);
        return $this->dataDeleted();
    }
}
