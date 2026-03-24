<?php 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Profile\PasswordController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/verify_user_email', [AuthController::class, 'verifyUserEmail']);
Route::post('auth/resend_email_verification_link', [AuthController::class, 'resendEmailVerificationLink']);
Route::middleware(['auth'])->group(function() {
    Route::post('/change_password', [PasswordController::class, 'changeUserPassword']);
});