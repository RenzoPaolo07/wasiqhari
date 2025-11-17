<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Voluntario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function login()
    {
        return view('user.login', ['title' => 'Iniciar Sesión', 'page' => 'login']);
    }
    
    public function register()
    {
        return view('user.register', ['title' => 'Registrarse', 'page' => 'register']);
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

        return back()->withErrors(['email' => 'Las credenciales no coinciden.']);
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
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        if ($request->role === 'voluntario') {
            Voluntario::create([
                'user_id' => $user->id,
                'telefono' => $request->phone,
                'estado' => 'Activo',
                'disponibilidad' => 'Flexible', // Valor por defecto para evitar error
                'fecha_registro' => now()
            ]);
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', '¡Bienvenido a WasiQhari!');
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Validaciones para cambio de contraseña (opcional en el mismo form)
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile')->withErrors($validator)->withInput();
        }

        // 1. Actualizar Avatar
        if ($request->hasFile('avatar')) {
            // Borrar avatar anterior si existe y no es default
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 2. Actualizar Datos Básicos
        $user->name = $request->name;
        $user->email = $request->email;

        // 3. Actualizar Contraseña (si se envió)
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // 4. Actualizar Datos de Voluntario
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

        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente.');
    }
}