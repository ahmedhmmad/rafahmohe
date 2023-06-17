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
            'id' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('id', $request->id)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'id' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;

    }

    public function logout(Request $request)
    {
        $user = User::where('id', $request->id)->first();
       if($user) {
              $user->tokens()->delete();
              return response()->json([
                  'message' => 'logout success'
              ]);

       }
        return response()->noContent();
    }
}
