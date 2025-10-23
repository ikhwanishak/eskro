<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendTAC;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function sendTAC(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $tac = rand(100000, 999999);

        Session::put("login_tac:$email", $tac);

        Mail::to($email)->send(new SendTAC($tac));

        return view('auth.verify_tac', ['email' => $email]);
    }

    public function verifyTAC(Request $request)
    {
        $email = $request->input('email');
        $code = $request->input('code');
        $expected = Session::get("login_tac:$email");

        if ($expected != $code) {
            return back()->with('error', 'Incorrect TAC code.')->withInput();
        }

        session(['creator_email' => $email]);

        return redirect()->route('transaction.create');
    }
}
