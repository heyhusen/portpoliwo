<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Resources\Users as UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $data = DB::table('users')
                    ->orderBy($request->sort_field, $request->sort_order)
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
     * @param  \App\User  $user
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
     * @param  \App\User  $user
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
            $name = $data->id . '/avatar-' . md5($data->id)  . date('-Y-m-d-H-m-s.') . $request->photo->extension();
            $request->photo->storeAs('avatar', $name);
        }
        return $name;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        User::destroy($request->selectedData);
        return $this->dataDeleted();
    }
}
