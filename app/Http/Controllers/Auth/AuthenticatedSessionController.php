<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    public function create(): View
    {
        return view('auth.login');
    }

    protected function authenticated(Request $request, $user)
{
    if ($user->role == 'doctor') {
        return redirect('/doctor/doashbord');
    } elseif ($user->role == 'patient') {
        return redirect('/patient/home');
    }

    return redirect('/');
}

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
        $user = Auth::user(); // Récupérez l'utilisateur authentifié
        if ($user->role == 'doctor') {
            return redirect('/doctor/doashbord');
        } elseif ($user->role == 'patient') {
            return redirect('/patient/home');
            if ($user->role == 'admine') {
                return redirect('/admine/profile');
            } elseif ($user->role == 'patient') {
                return redirect('/patient/home');}
        }
    
        // Redirection par défaut
        return redirect(RouteServiceProvider::HOME);
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
