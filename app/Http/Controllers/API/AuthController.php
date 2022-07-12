<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(LoginRequest $request)
    {
        $user = User::where('email', request('email'))->first();
        if ($user) {
            if (Hash::check(request('password'), $user->password)) {
                $user->save();

                return response()->json(['api_token' => $user->api_token], 200);
            } else {
                return response()->json(['error' => 'Verfiy password'], 403);
            }
        } else {
            return response()->json(['error' => 'Verfiy email'], 403);
        }

        return ['api_token' => $user->api_token];
    }

    /**
     * Destroy an authenticated session, create a new  api_token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $user = User::where('api_token', request('api_token'))->first();
        if ($user) {
            $user->api_token = Str::random(60);
            $user->save();

            return ['message' => 'Logout successful'];
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    /**
     * Create an user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'api_token' => Str::random(60),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return response()->json(['message' => 'User created successfully', 'api_token' => $user->api_token], 201);
    }
}
