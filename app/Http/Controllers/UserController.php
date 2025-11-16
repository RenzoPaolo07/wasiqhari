<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Voluntario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login()
    {
        $data = [
            'title' => 'Iniciar Sesión - WasiQhari',
            'page' => 'login'
        ];
        
        return view('user.login', $data);
    }
    
    public function register()
    {
        $data = [
            'title' => 'Registrarse - WasiQhari',
            'page' => 'register'
        ];
        
        return view('user.register', $data);
    }
    
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:voluntario,familiar,organizacion',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                            ->withErrors($validator)
                            ->withInput();
        }

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        // Si es voluntario, crear perfil de voluntario
        if ($request->role === 'voluntario') {
            Voluntario::create([
                'user_id' => $user->id,
                'telefono' => $request->phone,
                'direccion' => '',
                'distrito' => '',
                'habilidades' => '',
                
                // ================== LA CORRECCIÓN ==================
                // En lugar de '', ponemos un valor válido del ENUM.
                // 'Flexible' es una buena opción por defecto.
                'disponibilidad' => 'Flexible',
                // ===================================================

                'zona_cobertura' => '',
                'estado' => 'Activo',
                'fecha_registro' => now()
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')
                        ->with('success', 'Usuario registrado correctamente.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    
    public function profile()
    {
        $user = Auth::user();
        $voluntario = null;

        if ($user->role === 'voluntario') {
            $voluntario = Voluntario::where('user_id', $user->id)->first();
        }

        $data = [
            'title' => 'Mi Perfil - WasiQhari',
            'page' => 'profile',
            'user' => $user,
            'voluntario' => $voluntario
        ];
        
        return view('user.profile', $data);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile')
                            ->withErrors($validator)
                            ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        // Actualizar perfil de voluntario si existe
        if ($user->role === 'voluntario') {
            $voluntario = Voluntario::where('user_id', $user->id)->first();
            if ($voluntario) {
                $voluntario->update([
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                    'distrito' => $request->distrito,
                    'habilidades' => $request->habilidades,
                    'disponibilidad' => $request->disponibilidad,
                    'zona_cobertura' => $request->zona_cobertura
                ]);
            }
        }

        return redirect()->route('profile')
                        ->with('success', 'Perfil actualizado correctamente.');
    }
}