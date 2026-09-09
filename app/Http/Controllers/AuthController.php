<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Login casero (sin laravel/ui ni breeze, para no sumar dependencias) --
 * gatea el alta de negocios: solo dueños registrados pueden crear/editar
 * páginas. Ver rutas en routes/web.php dentro del grupo 'auth'.
 */
class AuthController extends Controller
{
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:15|unique:users,dni',
            'email' => 'required|email|max:255|unique:users,email',
            'telefono' => 'required|string|max:30',
            'nombre_negocio' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $datos['name'],
            'apellido' => $datos['apellido'],
            'dni' => $datos['dni'],
            'email' => $datos['email'],
            'telefono' => $datos['telefono'],
            'nombre_negocio' => $datos['nombre_negocio'] ?? null,
            'password' => Hash::make($datos['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/chat/negocio');
    }

    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credenciales, $request->boolean('recordar'))) {
            return back()->withErrors(['email' => 'Email o contraseña incorrectos.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended('/mis-negocios');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/inicio');
    }
}
