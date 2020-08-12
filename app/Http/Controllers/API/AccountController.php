<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Resources\Users as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
     * Display a listing of the resource for datatable
     *
     * @param Request $request
     * @return void
     */
    public function list(Request $request)
    {
        $data = User::orderBy($request->sort_field, $request->sort_order)
                    ->select('id', 'name', 'email', 'photo', 'created_at')
                    ->paginate($request->per_page);
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
     * Get authenticated user
     *
     * @param Request $request
     * @return void
     */
    public function me(Request $request)
    {
        $data = $request->user('sanctum');
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
        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
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
    public function uploadPhoto($request, $data, $name = 'default.png')
    {
        if ($request->hasFile('photo')) {
            Image::load($request->photo)
                ->fit(Manipulations::FIT_CROP, 256, 256)
                ->save();
            $name = $data->id . '/avatar-' . md5($data->id)  . date('-Y-m-d-H-m-s.') . $request->photo->extension();
            $request->photo->storeAs('public/avatar', $name);
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
