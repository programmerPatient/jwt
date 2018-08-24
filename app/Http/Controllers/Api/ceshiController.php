<?php

namespace App\Http\Controllers\Api;


// use Auth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;

class ceshiController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('myauth', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['account', 'password']);

        if (! $token = auth()->guard('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // return Auth::guard('admin')->user();
        return $this->respondWithToken($token)->setStatusCode(201);
    }


    private function respondWithToken($token, $extend = [])
    {
        return $this->response->array(array_merge([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
        ], $extend));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        // return response()->guard('admin')->json(auth()->user());
        // return auth('admin')->user();
        $user = Auth::guard('admin')->user();
        return response($user, 200);
    }

}
