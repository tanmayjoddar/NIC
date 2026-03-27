<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OtpToken;

class AuthController extends Controller
{
    public function signupPage()
    {
        return view('signup');
    }

    public function signupSubmit(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone'    => 'required|string|max:15',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // auto hashed by model
            'phone'    => $request->phone,
        ]);

        return redirect('/signin')->with('success', 'Account created! Please sign in.');
    }

    public function signinPage()
    {
        return view('signin');
    }

    public function signinSubmit(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'captcha'  => 'required',
        ]);

        // ── CAPTCHA Check ──────────────────────────────
        if ($request->captcha !== session('captcha_answer')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect CAPTCHA answer. Please refresh and try again.',
                ], 422);
            }
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Incorrect CAPTCHA answer. Please try again.');
        }

        // ── Credential Check ───────────────────────────
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password.',
                ], 422);
            }
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password!');
        }

        // ── Generate OTP ───────────────────────────────
        OtpToken::where('email', $user->email)->delete();
        $otp = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);
        OtpToken::create([
            'email'      => $user->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);
        session(['otp_email' => $user->email]);

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'redirect' => '/otp',
            ]);
        }

        return redirect('/otp')->with('success', 'OTP generated. Check the database `otp_tokens` table.');
    }

    public function logout()
    {
        session()->forget('user');
        session()->forget('otp_email');
        return redirect('/signin')->with('success', 'Logged out!');
    }
}
