@extends('layouts.app')

{{-- Definimos el título específico para esta página --}}
@section('title', $title ?? 'WasiQhari - Registro')

{{-- Contenido de la página --}}
@section('content')
    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-heart"></i>
                        <span>WasiQhari</span>
                    </div>
                    <h2>Crear Cuenta</h2>
                    <p>Únete a nuestra comunidad solidaria</p>
                </div>
                
                <form class="auth-form" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nombre Completo *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Tu nombre completo">
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="tu@email.com">
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" required placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar"></div>
                            <span class="strength-text">Seguridad de la contraseña</span>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repite tu contraseña">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">¿Cómo quieres participar? *</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user-tag"></i>
                            <select id="role" name="role" required>
                                <option value="">Selecciona tu rol</option>
                                <option value="voluntario" {{ old('role') == 'voluntario' ? 'selected' : '' }}>Voluntario</option>
                                <option value="familiar" {{ old('role') == 'familiar' ? 'selected' : '' }}>Familiar de adulto mayor</option>
                                <option value="organizacion" {{ old('role') == 'organizacion' ? 'selected' : '' }}>Organización/ONG</option>
                            </select>
                        </div>
                        @error('role')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group" id="organizationField" style="display: none;">
                        <label for="organization_name">Nombre de la Organización</label>
                        <div class="input-with-icon">
                            <i class="fas fa-building"></i>
                            <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" placeholder="Nombre de tu organización">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <div class="input-with-icon">
                            <i class="fas fa-phone"></i>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+51 XXX XXX XXX">
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="terms" required>
                            <span class="checkmark"></span>
                            Acepto los <a href="#" class="link">términos y condiciones</a> y la <a href="#" class="link">política de privacidad</a>
                        </label>
                        @error('terms')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="newsletter" {{ old('newsletter') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Quiero recibir noticias y actualizaciones de WasiQhari
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full btn-auth">
                        <i class="fas fa-user-plus"></i> Crear Cuenta
                    </button>
                    
                    <div class="auth-divider">
                        <span>o regístrate con</span>
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
                    <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
                </div>
            </div>
            
            <div class="auth-welcome">
                <div class="welcome-content">
                    <h2>Únete a Nuestra Misión</h2>
                    <p>Forma parte de esta increíble comunidad que está transformando la vida de adultos mayores en situación de vulnerabilidad.</p>
                    
                    <div class="benefits-grid">
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h4>Impacto Real</h4>
                            <p>Marca la diferencia en la vida de personas que realmente necesitan tu ayuda</p>
                        </div>
                        
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Comunidad Solidaria</h4>
                            <p>Conecta con personas increíbles que comparten tu pasión por ayudar</p>
                        </div>
                        
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <h4>Reconocimiento</h4>
                            <p>Recibe certificados y reconocimientos por tu labor voluntaria</p>
                        </div>
                        
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>Crecimiento Personal</h4>
                            <p>Desarrolla nuevas habilidades y experiencias enriquecedoras</p>
                        </div>
                    </div>
                    
                    <div class="stats-preview">
                        <div class="stat-preview">
                            <h3>500+</h3>
                            <p>Adultos Mayores Ayudados</p>
                        </div>
                        <div class="stat-preview">
                            <h3>150+</h3>
                            <p>Voluntarios Activos</p>
                        </div>
                        <div class="stat-preview">
                            <h3>98%</h3>
                            <p>Satisfacción</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Empujamos los scripts específicos al final del <body> --}}
@push('scripts')
    <script>
    // Mostrar/ocultar campo de organización
    document.getElementById('role').addEventListener('change', function() {
        const organizationField = document.getElementById('organizationField');
        if (this.value === 'organizacion') {
            organizationField.style.display = 'block';
        } else {
            organizationField.style.display = 'none';
        }
    });

    // Validación de contraseña
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.querySelector('.strength-bar');
        const strengthText = document.querySelector('.strength-text');
        
        let strength = 0;
        let text = 'Débil';
        let color = '#e74c3c';
        
        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
        if (password.match(/\d/)) strength += 25;
        if (password.match(/[^a-zA-Z\d]/)) strength += 25;
        
        if (strength >= 75) {
            text = 'Fuerte';
            color = '#27ae60';
        } else if (strength >= 50) {
            text = 'Media';
            color = '#f39c12';
        } else if (strength >= 25) {
            text = 'Débil';
            color = '#e74c3c';
        } else {
            text = 'Muy débil';
            color = '#c0392b';
        }
        
        strengthBar.style.width = strength + '%';
        strengthBar.style.background = color;
        strengthText.textContent = text;
        strengthText.style.color = color;
    });

    // Validación de confirmación de contraseña
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.style.borderColor = '#e74c3c';
        } else {
            this.style.borderColor = '#27ae60';
        }
    });

    // Inicializar campo de organización si ya estaba seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value === 'organizacion') {
            document.getElementById('organizationField').style.display = 'block';
        }
    });
    </script>
@endpush

{{-- Empujamos los estilos específicos al <head> --}}
@push('styles')
<style>
    /* Copiamos los mismos estilos de .auth-section de login */
    .auth-section {
        padding-top: 100px; /* Ajusta este valor a la altura de tu header */
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding-bottom: 20px;
        box-sizing: border-box;
    }

    /* Copiamos los mismos estilos de .auth-container y .auth-card de login */
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
    
    /* ... (puedes copiar el resto de estilos de login.blade.php aquí) ... */
    .auth-header { text-align: center; margin-bottom: 40px; }
    .auth-logo { display: flex; align-items: center; justify-content: center; gap: 10px; color: var(--primary-color); font-size: 1.5rem; font-weight: bold; margin-bottom: 20px; }
    .auth-logo i { font-size: 2rem; }
    .auth-header h2 { color: var(--dark-color); margin-bottom: 10px; font-size: 2rem; }
    .auth-header p { color: var(--text-light); }
    .auth-form { margin-bottom: 30px; }
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-light); }
    .input-with-icon input, .input-with-icon select { width: 100%; padding: 12px 15px 12px 45px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; transition: var(--transition); appearance: none; }
    .input-with-icon select { padding: 12px 45px; /* Ajuste para select */ }
    .input-with-icon input:focus, .input-with-icon select:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1); }
    .error-message { color: #e74c3c; font-size: 0.8rem; margin-top: 5px; display: block; }
    .form-options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; } /* Ajuste de margen */
    .checkbox-container { display: flex; align-items: center; cursor: pointer; color: var(--text-light); font-size: 0.9rem; }
    .checkbox-container input { display: none; }
    .checkmark { width: 18px; height: 18px; border: 2px solid #ddd; border-radius: 4px; margin-right: 8px; position: relative; transition: var(--transition); }
    .checkbox-container input:checked + .checkmark { background: var(--primary-color); border-color: var(--primary-color); }
    .checkbox-container input:checked + .checkmark:after { content: '✓'; position: absolute; color: white; font-size: 12px; top: 50%; left: 50%; transform: translate(-50%, -50%); }
    .btn-auth { margin-bottom: 20px; }
    .auth-divider { text-align: center; margin: 25px 0; position: relative; color: var(--text-light); font-size: 0.9rem; }
    .auth-divider:before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e0e0e0; }
    .auth-divider span { background: white; padding: 0 15px; position: relative; }
    .social-auth { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px; }
    .btn-social { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 12px; border: 2px solid #e0e0e0; background: white; color: var(--text-color); transition: var(--transition); }
    .btn-social:hover { border-color: var(--primary-color); transform: translateY(-2px); }
    .btn-google:hover { background: #4285F4; color: white; border-color: #4285F4; }
    .btn-facebook:hover { background: #3b5998; color: white; border-color: #3b5998; }
    .auth-footer { text-align: center; color: var(--text-light); }
    .auth-footer a { color: var(--primary-color); text-decoration: none; font-weight: 500; }
    .auth-footer a:hover { text-decoration: underline; }
    .auth-welcome { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 50px; display: flex; align-items: center; }
    .welcome-content h2 { font-size: 2.2rem; margin-bottom: 20px; }
    .welcome-content p { margin-bottom: 40px; opacity: 0.9; line-height: 1.6; font-size: 1.1rem; }

    /* Estilos específicos de register.blade.php */
    .benefits-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin: 30px 0;
    }

    .benefit-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        transition: var(--transition);
    }

    .benefit-card:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-5px);
    }

    .benefit-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.5rem;
    }

    .benefit-card h4 {
        margin: 0 0 10px 0;
        font-size: 1.1rem;
    }

    .benefit-card p {
        margin: 0;
        font-size: 0.8rem;
        opacity: 0.9;
        line-height: 1.4;
    }

    .stats-preview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 30px;
    }

    .stat-preview {
        text-align: center;
    }

    .stat-preview h3 {
        font-size: 2rem;
        margin: 0 0 5px 0;
        font-weight: bold;
    }

    .stat-preview p {
        margin: 0;
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .password-strength {
        margin-top: 8px;
    }

    .strength-bar {
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        margin-bottom: 4px;
        transition: var(--transition);
        width: 0%;
    }

    .strength-text {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    .link {
        color: var(--primary-color);
        text-decoration: none;
    }

    .link:hover {
        text-decoration: underline;
    }
    
    /* Responsive */
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
        
        .benefits-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-preview {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
</style>
@endpush