<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class LoginController extends Controller
{
    use AuthenticatesUsers;

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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $user = $this->guard()->user();
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
        return $this->authenticated($request, $this->guard()->user())
                ?: ResponseBuilder::success($data);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return ResponseBuilder::error(401, null, [trans('auth.failed')], 401);
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
        $this->guard()->user()->token()->revoke();
        return $this->loggedOut($request) ?: ResponseBuilder::success();
    }
}
