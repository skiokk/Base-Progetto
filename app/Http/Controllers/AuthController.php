<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Mostra la vista di login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gestisce il tentativo di login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Poiché Laravel utilizza 'email' come campo di default, ma noi vogliamo utilizzare 'username'
        if (Auth::attempt(['name' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Le credenziali fornite non sono corrette.',
        ])->onlyInput('username');
    }

    /**
     * Esegue il logout dell'utente
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Mostra la dashboard
     */
    public function dashboard()
    {
        return view('dashboard');
    }
    
    /**
     * Crea un utente di prova se non esiste
     */
    public function createTestUser()
    {
        // Solo per scopi di test, creiamo un utente admin
        if (!User::where('name', 'admin')->exists()) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
            return back()->with('message', 'Utente di test creato con successo. Username: admin, Password: password');
        }
        
        return back()->with('message', 'L\'utente di test esiste già');
    }
}