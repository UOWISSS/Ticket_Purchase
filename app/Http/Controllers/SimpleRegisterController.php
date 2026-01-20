<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SimpleRegisterController extends Controller
{
    public function create()
    {
        return view('simple_register');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);


        $redirect = $request->input('previous') ?? url()->previous();

        return redirect()->to($redirect)->with('success', 'a bejelentkezés sikere');
    }
}
