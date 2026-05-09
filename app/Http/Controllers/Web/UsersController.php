<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        $user->assignRole('Donor');

        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }

    public function doLogout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    public function profile(Request $request) {
        $user = Auth::user();
        $user->load(['donations.campaign', 'volunteerRegistrations.task']);
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

    public function forgotPassword() {
        return view('users.forgot-password');
    }

    public function doForgotPassword(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Simulation for presentation/testing
        $resetLink = route('reset_password', ['token' => $token]) . '?email=' . urlencode($request->email);
        
        return redirect()->back()->with('success', "A password reset link has been generated (Testing Mode): <br><a href='$resetLink'>$resetLink</a>");
    }

    public function resetPassword($token, Request $request) {
        return view('users.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function doResetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $reset = DB::table('password_reset_tokens')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();

        if (!$reset) {
            return back()->with('error', 'Invalid token or email.');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('success', 'Password has been reset successfully!');
    }

    public function index()
    {
        // Security Check: Only manage_users permission can access
        if(!auth()->user()->hasPermissionTo('manage_users')) {
            abort(401, 'Unauthorized access.');
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }
}
