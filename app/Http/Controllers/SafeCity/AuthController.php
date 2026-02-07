<?php

namespace App\Http\Controllers\SafeCity;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegister() { return view('frontend.auth.register'); }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login.form')->with('success', 'Registered! Please login.');
    }

    public function showLogin() { return view('frontend.auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('user/dashboard');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login.form');
    }

    public function showForgot() { return view('frontend.auth.forgot'); }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) return back()->with('error', 'Email not found');

        $token = \Str::random(60);
        $user->reset_token = $token;
        $user->save();

        // $link = route('password.reset', $token);

        // Mail::raw("Reset your password: $link", function ($msg) use ($user) {
        //     $msg->to($user->email)->subject('Password Reset');
        // });

        $link = route('password.reset', $token);

        Mail::send( 'emails.password_reset', ['link' => $link, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Reset Your Password');
        });

        return back()->with('email_success', 'Reset link sent to your email.');
    }

    public function showReset($token)
    {
        $user = User::where('reset_token', $token)->firstOrFail();
        return view('frontend.auth.reset', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('reset_token', $request->token)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
        ]);

        return redirect()->route('login.form')->with('success', 'Password updated!');
    }
}
