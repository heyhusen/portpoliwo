<?php

namespace App\Http\Controllers\API\Auth;

use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Users as UserResource;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

     /**
     * Register new user
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password']
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error(422, null, collect($validator->errors())->toArray(), 422);
        }
        $request->merge(['password' => Hash::make($request->password)]);
        $user = User::create($request->all());
        $user->assignRole('user');
        $userToken = $user->createToken('Portpoliwo Personal Access Token');
        $data = [
            'user' => $user,
            'token' => $userToken->accessToken,
            'type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $userToken->token->expires_at
            )->toDateTimeString()
        ];
        return ResponseBuilder::success($data);
    }
}
