<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialite; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
   
    public function create(): View
    {
        $specialites = Specialite::all(); // Récupère 
        return view('auth.register', compact('specialites')); 
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'lowercase'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:doctor,patient'],
            'specialite_id' => $request->role === 'doctor' ? 'required|exists:specialites,id' : 'nullable',
        ]);
       
        $user = User::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'doctor' && $request->has('specialite_id')) {
            Doctor::create([
                'user_id' => $user->id,
                'specialite_id' => $request->specialite_id,
            ]);
        }

        if ($request->role === 'patient') {
            Patient ::create([
                'user_id' => $user->id,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($request->role == 'doctor') {
            return redirect('/doctor/doashbord');
        } elseif ($request->role == 'patient') {
            return redirect('/patient/home');
        } else {
            return redirect('/register');
        }
        
    }}
    
  
    
