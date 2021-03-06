<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthApiController extends Controller
{
    public function authenticate(Request $request)
    {

        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {

            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {

                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = auth()->user();

        // all good so return the token
        return response()->json(compact('token', 'user'));
    }

    public function getAuthenticatedUser()
    {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['status' => 'Token is Expired']);

        } catch (TokenInvalidException $e) {

            return response()->json(['status' => 'Authorization Token invalid']);

        } catch (JWTException $e) {

            return response()->json(['status' => 'Authorization Token not found']);

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    public function refreshToken(Request $request)
    {

        if (!$token = JWTAuth::getToken()) {

            return response()->json(['error' => 'token_not_sent'], 401);
        }

        try {

           $token = JWTAuth::refresh(JWTAuth::getToken());

        } catch (TokenInvalidException $e) {

            return response()->json(['status' => 'Authorization Token invalid']);

        }

        return response()->json(compact('token'));
    }

}
