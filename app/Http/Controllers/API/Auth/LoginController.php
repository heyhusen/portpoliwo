<?php

namespace App\Http\Controllers\API\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['user', 'logout']);
    }

    /**
     * Authenticate the valid user
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember_me' => ['boolean']
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error(422, null, collect($validator->errors())->toArray(), 422);
        }
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $userToken = $user->createToken('Portpoliwo Personal Access Token');
            if ($request->remember_me) {
                $userToken->token->expires_at = Carbon::now()->addMonths(1);
                $userToken->token->save();
            }
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
        return ResponseBuilder::error(401, null, null, 401);
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
