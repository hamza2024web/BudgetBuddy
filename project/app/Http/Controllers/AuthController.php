<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $Fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $Fields["password"] = Hash::make($Fields["password"]);

        $user = User::create($Fields);

        $token = $user->createToken($request->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }
    public function login(Request $request){
        $Fields = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);
        $user = User::where('email',$Fields["email"])->first();
        
        if(!$user || !Hash::check($Fields["password"],$user->password)){
            return [
                'message' => 'The provided credentiels are incorrect.'
            ];
        }

        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return [
            'message' => 'You Are Logged Out.'
        ];
    }
}
