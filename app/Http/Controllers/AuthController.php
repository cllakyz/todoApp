<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register user func.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed'
        ]);

        if ($validators->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'errors' => $validators->errors()
            ], 422);
        }

        $user           = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json(['success' => true], 201);
    }

    /**
     * Login user func.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $login_data = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($login_data)) {
            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
        return response()->json(['success' => false, 'message' => 'Invalid Credentials!'], 400);
    }

    /**
     * User Logout func.
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Logged out Successfully.'
        ], 204);
    }

    /**
     * Get authenticated user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Refresh Jwt token
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    private function guard()
    {
        return Auth::guard();
    }
}
