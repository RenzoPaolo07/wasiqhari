<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Voluntario;
use App\Models\Visita; // ¡Importante! Agregamos el modelo Visita
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail; // <--- IMPORTANTE
use App\Mail\BienvenidaVoluntario;   // <--- IMPORTANTE

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
        // 1. VALIDACIÓN
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            // Aceptamos cualquier string en 'role' para no romper la validación, 
            // pero luego lo filtraremos abajo
            'role' => 'required|string', 
            'phone' => 'nullable|string|max:20',
            'terms' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

        // 2. FILTRO DE SEGURIDAD (¡NUEVO!)
        // Solo permitimos estos roles públicos. Cualquier otro se convierte en "voluntario".
        $rolSeguro = 'voluntario';
        if (in_array($request->role, ['voluntario', 'familiar'])) {
            $rolSeguro = $request->role;
        }
        // Si alguien manda "role" => "medico" o "admin", el sistema lo ignorará y pondrá "voluntario".

        // 3. CREACIÓN DEL USUARIO
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $rolSeguro // <--- Usamos la variable filtrada
        ]);

        if ($request->role === 'voluntario') {
            Voluntario::create([
                'user_id' => $user->id,
                'telefono' => $request->phone,
                'estado' => 'Activo',
                'disponibilidad' => 'Flexible', 
                'fecha_registro' => now()
            ]);
        }

        // --- ENVÍO DE CORREO DE BIENVENIDA ---
        try {
            Mail::to($user->email)->send(new BienvenidaVoluntario($user));
        } catch (\Exception $e) {
            // Si falla el correo, no detenemos el registro, solo lo logueamos
            \Log::error('Error enviando correo bienvenida: ' . $e->getMessage());
        }
        // -------------------------------------

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
        
        // Variables de Gamificación (Valores por defecto)
        $nivel = 'Novato';
        $puntos = 0;
        $proxNivel = 50;

        if ($user->role === 'voluntario') {
            $voluntario = Voluntario::where('user_id', $user->id)->first();
            
            if ($voluntario) {
                // Calcular puntos: 10 puntos por cada visita realizada
                $visitasCount = Visita::where('voluntario_id', $voluntario->id)->count();
                $puntos = $visitasCount * 10;
                
                // Lógica de Niveles
                if ($puntos >= 200) {
                    $nivel = 'Leyenda';
                    $proxNivel = 500; // Meta final
                } elseif ($puntos >= 100) {
                    $nivel = 'Experto';
                    $proxNivel = 200;
                } elseif ($puntos >= 50) {
                    $nivel = 'Comprometido';
                    $proxNivel = 100;
                } else {
                    $nivel = 'Novato';
                    $proxNivel = 50;
                }
            }
        }

        $data = [
            'title' => 'Mi Perfil - WasiQhari',
            'page' => 'profile',
            'user' => $user,
            'voluntario' => $voluntario,
            'nivel' => $nivel,        // <--- ¡Ahora sí enviamos estas variables!
            'puntos' => $puntos,      // <---
            'proxNivel' => $proxNivel // <---
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
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile')->withErrors($validator)->withInput();
        }

        // 1. Actualizar Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 2. Actualizar Datos Básicos
        $user->name = $request->name;
        $user->email = $request->email;

        // 3. Actualizar Contraseña
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // 4. Actualizar Datos de Voluntario (SOLO SI SE ENVIARON)
        if ($user->role === 'voluntario' && $request->has('disponibilidad')) {
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

        $mensaje = $request->hasFile('avatar') ? 'Foto de perfil actualizada.' : 'Perfil actualizado correctamente.';
        return redirect()->route('profile')->with('success', $mensaje);
    }
}