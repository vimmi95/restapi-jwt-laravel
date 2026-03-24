<?php
namespace App\Customs\Services;

use Illuminate\Support\Facades\Hash;

    class PasswordService 
    {

        private function validateCurrentPassword($current_password) {
            // if(!password_verify($current_password, auth()->user()->password)) {
            //     response()->json([
            //         'status' => 'Failed',
            //         'message' => 'Current password did not match.'
            //     ])->send();
            //     exit();

                    if (!Hash::check($current_password, auth()->user()->password)) {
                response()->json([
                    'status' => 'Failed',
                    'message' => 'Current password did not match.'
                ])->send();
                exit();
    }
            
        }
        public function changePassword($data) {
            //Update the password 
            $this->validateCurrentPassword($data['current_password']);
            $updatePassword = auth()->user()->update([
                'password' => Hash::make($data['password'])
            ]);
            if($updatePassword) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Password updated successfully'
                ]);                
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'An error occured while updating password'
                ]); 
            }
        }
    }
