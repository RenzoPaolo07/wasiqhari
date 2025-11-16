@extends('layouts.app')

{{-- Definimos el título específico para esta página --}}
@section('title', $title ?? 'WasiQhari - Iniciar Sesión')

{{-- Esta sección es el contenido que se inyectará en @yield('content') --}}
@section('content')
    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-heart"></i>
                        <span>WasiQhari</span>
                    </div>
                    <h2>Iniciar Sesión</h2>
                    <p>Accede a tu cuenta para continuar</p>
                </div>
                
                <form class="auth-form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="tu@email.com">
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" required placeholder="Tu contraseña">
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Recordar sesión
                        </label>
                        <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full btn-auth">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                    
                    <div class="auth-divider">
                        <span>o continúa con</span>
                    </div>
                    
                    <div class="social-auth">
                        <button type="button" class="btn btn-social btn-google">
                            <i class="fab fa-google"></i> Google
                        </button>
                        <button type="button" class="btn btn-social btn-facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                    </div>
                </form>
                
                <div class="auth-footer">
                    <p>¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
                </div>
            </div>
            
            <div class="auth-welcome">
                <div class="welcome-content">
                    <h2>Bienvenido de vuelta</h2>
                    <p>Ingresa a tu cuenta para acceder a todas las funcionalidades de WasiQhari y continuar haciendo la diferencia en tu comunidad.</p>
                    
                    <div class="welcome-features">
                        <div class="welcome-feature">
                            <i class="fas fa-hands-helping"></i>
                            <span>Gestiona tus visitas programadas</span>
                        </div>
                        <div class="welcome-feature">
                            <i class="fas fa-bell"></i>
                            <span>Recibe alertas importantes</span>
                        </div>
                        <div class="welcome-feature">
                            <i class="fas fa-chart-line"></i>
                            <span>Mira tu impacto social</span>
                        </div>
                        <div class="welcome-feature">
                            <i class="fas fa-users"></i>
                            <span>Conecta con otros voluntarios</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Empujamos los estilos específicos de esta página al <head> --}}
@push('styles')
<style>
    .auth-section {
        /* Ajustamos el min-height para que no sea 100vh sino que se adapte al contenido 
           o le restamos la altura del header si la sabemos. 
           O mejor, lo dejamos como estaba pero con un padding-top para el header.
           ¡Vamos a probar algo mejor!
        */
        padding-top: 100px; /* Ajusta este valor a la altura de tu header */
        min-height: 100vh; /* Mantenemos el 100vh para el fondo degradado */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding-bottom: 20px; /* Añadimos padding abajo */
        box-sizing: border-box; /* Importante para que el padding-top no sume al height */
    }

    .auth-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        max-width: 1000px;
        width: 100%;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    }

    .auth-card {
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .auth-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: var(--primary-color);
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .auth-logo i {
        font-size: 2rem;
    }

    .auth-header h2 {
        color: var(--dark-color);
        margin-bottom: 10px;
        font-size: 2rem;
    }

    .auth-header p {
        color: var(--text-light);
    }

    .auth-form {
        margin-bottom: 30px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
    }

    .input-with-icon input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: var(--transition);
    }

    .input-with-icon input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    }

    .error-message {
        color: #e74c3c;
        font-size: 0.8rem;
        margin-top: 5px;
        display: block;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        cursor: pointer;
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .checkbox-container input {
        display: none;
    }

    .checkmark {
        width: 18px;
        height: 18px;
        border: 2px solid #ddd;
        border-radius: 4px;
        margin-right: 8px;
        position: relative;
        transition: var(--transition);
    }

    .checkbox-container input:checked + .checkmark {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .checkbox-container input:checked + .checkmark:after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 12px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .forgot-password {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    .btn-auth {
        margin-bottom: 20px;
    }

    .auth-divider {
        text-align: center;
        margin: 25px 0;
        position: relative;
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .auth-divider:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e0e0e0;
    }

    .auth-divider span {
        background: white;
        padding: 0 15px;
        position: relative;
    }

    .social-auth {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 30px;
    }

    .btn-social {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px;
        border: 2px solid #e0e0e0;
        background: white;
        color: var(--text-color);
        transition: var(--transition);
    }

    .btn-social:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .btn-google:hover {
        background: #4285F4;
        color: white;
        border-color: #4285F4;
    }

    .btn-facebook:hover {
        background: #3b5998;
        color: white;
        border-color: #3b5998;
    }

    .auth-footer {
        text-align: center;
        color: var(--text-light);
    }

    .auth-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    .auth-welcome {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 50px;
        display: flex;
        align-items: center;
    }

    .welcome-content h2 {
        font-size: 2.2rem;
        margin-bottom: 20px;
    }

    .welcome-content p {
        margin-bottom: 40px;
        opacity: 0.9;
        line-height: 1.6;
        font-size: 1.1rem;
    }

    .welcome-features {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .welcome-feature {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .welcome-feature i {
        font-size: 1.2rem;
        opacity: 0.8;
    }

    .welcome-feature span {
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .auth-container {
            grid-template-columns: 1fr;
        }
        
        .auth-welcome {
            display: none;
        }
        
        .auth-card {
            padding: 30px 20px;
        }
        
        .social-auth {
            grid-template-columns: 1fr;
        }
        
        .form-options {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>
@endpush