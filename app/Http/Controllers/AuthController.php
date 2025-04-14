<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        // dd($request->all());

        $log = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // dd(Auth::attempt($log));

        if (Auth::attempt($log)) {
            return redirect('/');
        }

        

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}