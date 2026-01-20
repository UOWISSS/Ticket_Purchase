<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {

        $previous = url()->previous();
        if (!session()->has('url.intended') && $previous) {
            $base = url('/');
            $loginUrl = route('login');
            $registerUrl = route('register');
            if (strpos($previous, $base) === 0 && !str_contains($previous, $loginUrl) && !str_contains($previous, $registerUrl)) {
                session()->put('url.intended', $previous);
            }
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Sikeres bejelentkezés!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
