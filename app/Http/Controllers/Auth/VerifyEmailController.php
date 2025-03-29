<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(string $id, string $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals($hash, sha1($user->email))) {
            throw new AuthorizationException;
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        return redirect()->to('/')->with('verified', true);
    }
}
