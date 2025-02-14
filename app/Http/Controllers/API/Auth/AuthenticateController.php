<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Shop;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class AuthenticateController extends Controller
{

    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function login(LoginRequest $request) {
        $email = strtolower($request->email);
        $password = $request->password;
        $user = $this->userRepository->getByEmail($email);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ])->setStatusCode(404);
        }
        if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Email not verified'
            ])->setStatusCode(403);
        }

        if (Hash::check($password, $user->password)) {
            return response()->json([
                'token' => $user->createToken('token')->plainTextToken
            ]);
        }
        return response()->json([
            'message' => 'Invalid credentials'
        ])->setStatusCode(401);
    }

    public function loginShop(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $shop = Shop::where('email', $request->email)->first();

        if (!$shop || !Hash::check($request->password, $shop->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $shop->createToken('shop-token')->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    public function register(RegisterRequest $request) {
        $user = $this->userRepository->create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->image;
            $filename = now()->format('Y-m-d_H:i:s.u') . '.png';
            $path = 'user_images/'. $user->id .'/'. $filename;
            Storage::disk('s3')->put($path, file_get_contents($file), 'private');
        }

        $uri = str_replace('/', '+', $path);
        $user->update([
            'image_url' => env("APP_URL") . 'api/images/' . $uri
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function redirectToGoogle()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = $this->userRepository->updateOrCreate(
                [
                    'email' => $googleUser->email
                ],
                [
                    'name' => $googleUser->name,
                    'password' => '',
                    'image_url' => $googleUser->avatar,
                    'login_by' => 'google',
                    'email_verified_at' => now(),
                ]
            );
            return response()->json([
                'token' => $user->createToken('token')->plainTextToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Google login failed'], 500);
        }
    }
}
