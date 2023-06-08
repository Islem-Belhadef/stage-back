<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignupRequest;
use App\Mail\ConfirmEmail;
use App\Models\User;
use App\Models\Student;
use App\Models\VerificationCode;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Log in the user and generate a JWT token.
     *
     * @param LoginRequest $request
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
        $role = Auth::user()->role;
        $isVerified = (Auth::user()->email_verified_at)?true:false;

        return response()->json(compact('token', 'role', 'user','isVerified'));
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
            'first_name' => ' ',
            'last_name' => ' ',
            'email' => $request->email,
            'role'=> 0,
            'password' => bcrypt($request->password),
        ]);

        // Random 6 characters code to verify email address
        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code = $code . strval(rand(0, 9));
        }

        $verification = VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code
        ]);

        Mail::to($request->email)->send(new ConfirmEmail('Student', $code));

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            
            'token' => $token,
            'user' => $user,
            'role' => 0,
            'verification' => $verification

        ], 201);
    }

    /**
     * Handle creating a student request.
     *
     * @param SignupRequest $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();

        $student = Student::create([
            'department_id' => $request->department_id,
            'speciality_id' => $request->speciality_id,
            'semester' => $request->semester,
            'level' => $request->level,
            'academic_year' => $request->academic_year,
            'date_of_birth' => $request->date_of_birth,
            'user_id' => $user->id
        ]);

        return response()->json([
            'student' => $student,
            'role' => 0
        ], 201);
    }


    /**
     * Logout from the account
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Verify the user's email address.
     *
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $code = VerificationCode::where('user_id', $request->user()->id)->first();

        if ($code->code != $request->code) {
            return response()->json(['error' => 'Invalid verification code please try again'], 406);
        }

        $user = User::findOrFail($request->user()->id);
        $user->email_verified_at = date("Y-m-d h:i:sa");
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    }

    /**
     * Send a password reset link to the user.
     *
     * @param LoginRequest $request
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
     * @param ResetPasswordRequest $request
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


    public function getProfile(Request $request)
    {
        $user = $request->user()['id'];
        $role = $request->user()['role'];
        switch ($role) {
            case 0 :
            {
                $profile = User::with('student.speciality', 'student.department')->find($user);

                return response()->json(['profile' => $profile]);

            }
            case 1 :
            {
                $profile = User::with('hod.department')->find($user);

                return response()->json(['profile' => $profile]);
            }
            case 2 :
            {
                $profile = User::with('supervisor.company')->find($user);

                return response()->json(['profile' => $profile]);
            }
        }
        $profile = User::find($user);

        return response()->json(['profile' => $profile]);

    }
}
