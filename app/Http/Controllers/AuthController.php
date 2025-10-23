<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\SendTAC;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function sendTac(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $code = rand(100000, 999999);

        Session::put("auth_tac:$email", $code);
        Session::put("auth_email_attempt", $email);

        Mail::to($email)->send(new SendTAC($code));

        return view('auth.verify_login', [
            'email' => $email
        ]);
    }

    public function verifyTac(Request $request)
{
    $email = $request->input('email');
    $code = $request->input('code');
    $sessionKey = "auth_tac:$email";
    $attemptKey = "auth_tac_attempt:$email";

    // ❗ Simpan & kira bilangan percubaan
    $attempts = Session::get($attemptKey, 0) + 1;
    Session::put($attemptKey, $attempts);

    // ❌ Kalau lebih 3 kali gagal → Reset & paksa login semula
    if ($attempts > 3) {
        Session::forget($sessionKey);
        Session::forget($attemptKey);
        return redirect()->route('login')->with('error', 'Too many incorrect attempts. Please request a new TAC.');
    }

    // ❌ TAC salah → kekal di page verify_login
    if (Session::get($sessionKey) != $code) {
        return view('auth.verify_login', [
            'email' => $email,
            'error' => 'TAC code is incorrect. Attempt ' . $attempts . ' of 3.',
        ]);
    }

    // ✅ TAC betul → login seperti biasa
    Session::put('auth_email', $email);
    Session::forget($attemptKey); // reset count bila berjaya
    return redirect()->route('transaction.create');
}

    public function logout()
    {
        Session::forget('auth_email');
        return redirect()->route('login');
    }
}
