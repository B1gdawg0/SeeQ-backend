<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\DecryptResource;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RedirectResource;
use App\Mail\UserVerificationEmail;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function login(LoginRequest $request)
    {
        $email = strtolower($request->email);
        $password = $request->password;
        $user = $this->userRepository->getByEmail($email);

        if (!$user || (!$user->login_by == 'default') || !$user->email_verified_at) {
            return response()->json([
                'error' => 'User not found or not verified'
            ])->setStatusCode(404);
        }

        if (Hash::check($password, $user->password)) {
            $token = Crypt::encrypt($user->createToken('token', ['*'], now()->addDay())->plainTextToken);
            return LoginResource::make((object)
            [
                'token' => $token,
                'id' => $user->id,
                'role' => $user->role,
            ]
            )->response()->setStatusCode(201);
        }

        return response()->json([
            'error' => 'Invalid credentials'
        ])->setStatusCode(401);
    }

    public function register(RegisterRequest $request)
    {
        if ($this->userRepository->getByEmail(strtolower($request->email))) {
            return response()->json([
                'error' => 'Email already exists'
            ], 422);
        }

        DB::transaction(function () use ($request) {
            $user = $this->userRepository->create([
                'name' => $request->name,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'CUSTOMER',
            ]);

            $user->update([
                'image_url' => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            ]);

            Mail::to($user->email)->send(new UserVerificationEmail($user));
        });

        return response()->json(null, 201);
    }

    public function googleLogin(Request $request)
    {
        $user = $this->userRepository->updateOrCreate(
            [
                'email' => $request->email
            ],
            [
                'name' => $request->name,
                'password' => '',
                'image_url' => $request->image_url,
                'login_by' => 'google',
                'role' => 'CUSTOMER',
                'email_verified_at' => now(),
            ]
        );
        $token = Crypt::encrypt($user->createToken('token')->plainTextToken);
        return LoginResource::make((object)
        [
            'token' => $token,
            'id' => $user->id,
            'role' => $user->role,
        ]
        )->response()->setStatusCode(201);
    }

    public function decrypt(Request $request)
    {
        return DecryptResource::make((object)
        [
            'plain_text' => Crypt::decrypt($request->encrypted)
        ]
        )->response()->setStatusCode(201);
    }

    public function verify(int $user, string $token)
    {
        try {
            $user = $this->userRepository->getById($user);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        if (!hash_equals($token, sha1($user->email)) || $user->email_verified_at) {
            return response()->view('emails.verifystatus', [
                'status' => 'reject',
                'path_link' => url(env("{$user->role}_FRONTEND_URL") . '/login')
            ], 400);
        }

        $user->email_verified_at = now();
        $user->save();

        return response()->view('emails.verifystatus', ['status' => 'success', 'path_link' => url(env("{$user->role}_FRONTEND_URL") . '/login')], 200);
    }


    public function forgetPassword(Request $request)
    {
        $request->validate(['email' => 'required']);
        $email = strtolower($request->email);
        $user = $this->userRepository->getByEmail($email);

        DB::table('password_resets')->where('email', $email)->delete();

        $plainToken = Str::random(32);
        $hashedToken = Hash::make($plainToken);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $hashedToken,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(30)
        ]);

        Mail::send('emails.custom_reset', ['token' => $plainToken, 'role' => $user->role], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });

        return response()->json(['message' => 'Password reset link sent to your email!'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return response()->json(['error' => 'Invalid or expired token'], 400);
        }

        if (!Hash::check($request->token, $reset->token)) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $user = User::where('email', $reset->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 400);
        }

        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where('email', $reset->email)->delete();

        return response()->json(['message' => 'Password has been reset successfully!'], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(null, 204);
    }

}
