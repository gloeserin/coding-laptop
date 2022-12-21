<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {

        return view('login.login');
    }

    public function store(Request $request) 
    {
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth()->user()->is_admin == 1) {
                $request->session()->regenerate();
                return redirect()->intended('/index');
            }else{
                return redirect()->intended(route('indexPeminjaman'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not watch our records',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('/index');

    }
}
