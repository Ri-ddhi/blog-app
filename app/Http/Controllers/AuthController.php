<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/posts');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(){
        return view('register');
    }
    public function register (RegisterRequest $request)
    {
        $credentials = $request->validated();
        $user = User::create([
            'name'     => $credentials['name'],
            'email'    => $credentials['email'],
            'password' => Hash::make($credentials['password']), // Securely hashes the password
        ]);
        Auth::login($user);
        $request->session()->regenerate();
            return redirect()->intended('/posts');
        }

}
