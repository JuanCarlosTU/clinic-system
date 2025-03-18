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
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Lógica de redirección basada en roles:
        $user = Auth::user();

        if ($user->role == 'doctor') {
            return redirect()->intended('/doctor/dashboard'); // Redirige a /doctor/dashboard para doctores (RUTA DIRECTA)
        } elseif ($user->role == 'secretary') {
            return redirect()->intended('/dashboard'); // Redirige al dashboard de secretaria (RUTA DIRECTA)
        } else {
            return redirect()->intended('/dashboard'); // Redirección por defecto a dashboard (puedes cambiar a otra ruta si lo deseas)
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}