<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    /**
     * Log in the user and generate a JWT token.
     *
     * @param  LoginRequest  $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    /**
     * Handle a signup request.
     *
     * @param SignupRequest $request
     * @return JsonResponse
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $student = Student::create([
            'department_id' => $request->department_id,
            'speciality' => $request->speciality,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
            'date_of_birth' => $request->date_of_birth,
            'user_id' => $user->id
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'student' => $student,
        ], 201);
    }


    /**
     * Verify the user's email address.
     *
     * @param  EmailVerificationRequest  $request
     * @return JsonResponse
     */
    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json(['message' => 'Email verified successfully']);
    }

    /**
     * Send a password reset link to the user.
     *
     * @param  LoginRequest  $request
     * @return JsonResponse
     */
    public function sendPasswordResetLink(LoginRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent']);
        } else {
            return response()->json(['error' => 'Unable to send password reset link'], 500);
        }
    }

    /**
     * Reset the user's password.
     *
     * @param  ResetPasswordRequest  $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only(['email', 'password', 'password_confirmation', 'token']),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        } else {
            return response()->json(['error' => 'Unable to reset password'], 500);
        }
    }
}
