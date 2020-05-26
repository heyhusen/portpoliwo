<?php

namespace App\Http\Controllers\API;

use Auth;
use App\ApiCode;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Users as UserCollection;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class AuthController extends Controller
{
    /**
     * Register new user
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password']
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error(ApiCode::INVALID_INPUT, null, collect($validator->errors())->toArray(), 422);
        }
        $request->merge(['password' => Hash::make($request->password)]);
        $user = User::create($request->all());
        $user->assignRole('user');
        $data = [
            'user' => $user,
            'token' => $user->createToken('Portpoliwo')->accessToken
        ];
        return ResponseBuilder::success($data);
    }

    /**
     * Authenticate the valid user
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $data = [
                'user' => $user,
                'token' => $user->createToken('Portpoliwo')->accessToken
            ];
            return ResponseBuilder::success($data);
        }
        return ResponseBuilder::error(ApiCode::UNAUTHORIZED, null, null, 401);
    }

    /**
     * Get authenticated user
     *
     * @param Request $request
     * @return void
     */
    public function user(Request $request)
    {
        $data = auth('api')->user();
        return ResponseBuilder::success($data);
    }

    /**
     * Remove authenticated user
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return ResponseBuilder::success();
    }
}
