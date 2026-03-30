<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtpToken;
use App\Models\User;

class OtpController extends Controller
{
    /**
     * Show the OTP entry page.
     * Guard: only accessible if otp_email is in session.
     */
    public function showOtpPage()
    {
        if (!session('otp_email')) {
            return redirect('/signin')->with('error', 'Please sign in first.');
        }

        return view('otp');
    }

    /**
     * Verify the submitted OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('otp_email');

        if (!$email) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Session expired. Please sign in again.'], 422);
            }
            return redirect('/signin')->with('error', 'Session expired. Please sign in again.');
        }

        $record = OtpToken::where('email', $email)->latest()->first();

        if (!$record) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No OTP found. Please sign in again.'], 422);
            }
            return back()->with('error', 'No OTP found. Please sign in again.');
        }

        if (now()->greaterThan($record->expires_at)) {
            $record->delete();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'OTP has expired. Please sign in again.'], 422);
            }
            return back()->with('error', 'OTP has expired. Please sign in again.');
        }

        if (hash('sha256',$request->otp) !== $record->otp) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid OTP. Please try again.'], 422);
            }
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        // ── OTP valid ──────────────────────────────────
        $record->delete();
        $user = User::where('email', $email)->first();
        session(['user' => $user]);
        session()->forget('otp_email');

        if ($request->ajax()) {
            return response()->json(['success' => true, 'redirect' => '/form']);
        }

        return redirect('/form')->with('success', 'Welcome ' . $user->name . '! Verification successful.');
    }
}
