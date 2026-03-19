<?php

namespace App\Http\Controllers\Api;

use App\Customs\Services\EmailVerificationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ResendEmailVerificationLinkRequest;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private EmailVerificationService $service)
    {
        //throw new \Exception('Not implemented');
    }

    public function login(LoginRequest $request) {
        $token = auth()->attempt($request->validated());
        if ($token) {
            return $this->getAccessToken($token, auth()->user());   
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Invalid User' 
            ], 401);
        }
    }

    /**
     * Get JWT access token 
    */
    public function getAccessToken($token, $user) {
        /* Login to create a user record*/
        return response()->json([
            'status' => 'success',
            'type' => 'Bearer',
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

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        /* Login to create a user record*/
        $user = User::create($data);
        /*Check if the user was created*/
        if ($user) {
            $this->service->sendVerificationLink($user);
           
            $token = auth()->login($user);   
            return $this->getAccessToken($token, $user);         
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not craeted'
            ],500); 
        }
    }

    /**
     * Verify Email of the user
     */
    public function verifyUserEmail(VerifyEmailRequest $request) {
        return $this->service->verifyEmail($request->email, $request->token);
    }     

    /**
     * Resend a verification link 
     */
    public function resendEmailVerificationLink(ResendEmailVerificationLinkRequest $request) {
        return $this->service->resendLink($request->email);
    }  
}
