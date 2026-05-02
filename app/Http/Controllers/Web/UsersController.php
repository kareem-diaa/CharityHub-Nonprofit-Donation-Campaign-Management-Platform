<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())->with('error', 'Invalid login information.');
        }

        $user = Auth::user();
        
        // Removed email verification check temporarily to simplify dev, but here is the lecture logic:
        /*
        if(!$user->email_verified_at) {
            Auth::logout();
            return redirect()->back()->withInput($request->input())->with('error', 'Your email is not verified.');
        }
        */

        return redirect('/');
    }

    public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        // Assign default role 'Donor'
        // $user->assignRole('Donor');

        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }

    public function doLogout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    public function profile(Request $request) {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    public function redirectToGoogle() {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
            
            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);

            Auth::login($user);
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed.');
        }
    }
}
