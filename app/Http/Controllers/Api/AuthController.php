<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // find user 
        $user = User::where('email', $request->email)->first();

        // check the email with message if false
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['this provided credentials are incorrect']
            ]);
        }

        // check from the password using hash
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['this provided credentials are incorrect']
            ]);
        }

        // generate token using createtoken() in the built in trait call HasApiTokens that used in User model
        // createtoken(): it basically create and store a new token inside the database
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'logged out successfully'
        ]);
    }
}
