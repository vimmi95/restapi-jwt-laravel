<?php

namespace App\Customs\Services;

use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EmailVerificationService {


    /**
     * Send email to the user
     */
    public function sendVerificationLink(object $user): void   {
        Notification::send($user, new EmailVerificationNotification($this->generateVerificationLink($user->email)));
    }

    /**
     * generate verification link which will be send through an email
     */
    public function generateVerificationLink(string $email): string
    {
        $checkIfTokenExists = EmailVerificationToken::where('email', $email)->first();
        if($checkIfTokenExists) $checkIfTokenExists->delete();
        $token = Str::uuid();
        $url = config('app.url'). "?token=". $token . "&email=".$email;
        $saveToken = EmailVerificationToken::create([
            "email" => $email,
            "token" => $token,
            "expired_at" => now()->addMinutes(60),
        ]);

        if($saveToken) {
            return $url;
        }
        throw new \Exception("Unable to create verification token");
    }

    /**
     * verify the token send to the user
     */
    public function verifyToken(string $email, string $token){
        $token = EmailVerificationToken::where('email', $email)->where('token',$token)->first();
        if($token) {
            // check token expiry
            if($token->expired_at >= now()){
                return $token;
            } else {
                $token->delete();
                response()->json([
                    'status' => 'Failed',
                    'message' => 'Token Expired'
                ])->send();
                exit; 
            }
        } else {
            response()->json([
                'status' => 'Failed',
                'message' => 'Invalid Token'
            ])->send();
            exit; 
        }
    }

    /** check if the Email has already been verified  */
    public function checkEmailIsVerified($user) {
        if($user->email_verified_at){
            response()->json([
                'status' => 'Failed',
                'message' => 'Email has already been verified'
            ])->send();
            exit;
        }
    }

    public function verifyEmail(string $email, string $token) {
        $user = User::where('email', $email)->first();  
        if(!$user) {
            response()->json([
                'status'=>'Failed',
                'message'=>'User not found'
            ])->send();
            exit;
        }

        $this->checkEmailIsVerified($user);
        $verifiedToken = $this->verifyToken($email, $token);
        if($user->markEmailAsVerified()) {
            $verifiedToken->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Email has been verified successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Verification Failed'
            ]);
        }
    }


    /**Resend link to the token */
    public function resendLink($email){
        $user = User::where("email", $email)->first();
        if($user){
            $this->sendVerificationLink($user);
              return response()->json([
                'status' => 'Success',
                'message' => 'Verification link send'
            ]);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not found'
            ]);
        }
    }
    

}