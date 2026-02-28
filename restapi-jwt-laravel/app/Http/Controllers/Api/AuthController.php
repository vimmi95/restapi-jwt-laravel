<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        
    }

    /**
     * Get JWT access token 
    */
    public function getAccessToken($token, $user) {
        /* Login to create a user record*/
        return response()->json([
            'status' => 'success',
            'type' => 'bearer',
            'user' => $user,
            'access_token' => $token
        ]);
    }

    /**
     * Add users to the user table
     * Validate the columns
     * And if the user is created than login
     * 
    */
    public function register(RegistrationRequest $request) {
        /* Login to create a user record*/
        $user = User::create($request->validated());

        /*Check if the user was created*/
        if ($user) {
            $token = auth()->login($user);   
            return $this->getAccessToken($token, $user);         
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not craeted'
            ],500); 
        }
    }
}
