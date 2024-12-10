<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'name'; // Utiliza el campo "name" como identificador de inicio de sesión
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        // Intento de inicio de sesión
        if ($this->guard()->attempt($credentials, $request->filled('remember'))) {
            // Verificar si el nombre de usuario es "admin"
            if (Auth::user()->name === 'admin') {
                return true; // Inicio de sesión exitoso para el usuario "admin"
            } else {
                // Si el nombre de usuario no es "admin", desconectar al usuario
                $this->guard()->logout();
            }
        }

        return false; // Inicio de sesión fallido
    }
}