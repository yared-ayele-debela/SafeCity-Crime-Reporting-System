<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    //
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'provider_id' => $googleUser->getId(),
                    'password' => bcrypt(uniqid()),
                    'status' =>1,
                ]);
            }

            Auth::login($user);

            return redirect('/my-cart');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
