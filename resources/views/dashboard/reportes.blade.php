<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WasiQhari - Registro' }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Estilos CSS -->
    <style>
    :root {
        --primary-color: #e74c3c;
        --secondary-color: #c0392b;
        --dark-color: #2c3e50;
        --text-color: #34495e;
        --text-light: #7f8c8d;
        --background-color: #f8f9fa;
        --success-color: #27ae60;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --transition: all 0.3s ease;
        --shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: var(--text-color);
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .auth-section {
        width: 100%;
        max-width: 1200px;
    }

    .auth-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow);
        overflow: hidden;
        min-height: 800px;
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
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .auth-logo i {
        font-size: 2.5rem;
    }

    .auth-header h2 {
        color: var(--dark-color);
        margin-bottom: 10px;
        font-size: 2.2rem;
    }

    .auth-header p {
        color: var(--text-light);
        font-size: 1.1rem;
    }

    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.9rem;
    }

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-icon i {
        position: absolute;
        left: 15px;
        color: var(--text-light);
        z-index: 2;
    }

    .input-with-icon input,
    .input-with-icon select {
        width: 100%;
        padding: 15px 15px 15px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
    }

    .input-with-icon input:focus,
    .input-with-icon select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
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

    .form-options {
        margin: 10px 0;
    }

    .checkbox-container {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        cursor: pointer;
        font-size: 0.9rem;
        color: var(--text-color);
    }

    .checkbox-container input[type="checkbox"] {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .checkmark:after {
        content: "✓";
        color: white;
        font-size: 12px;
        opacity: 0;
        transition: var(--transition);
    }

    .checkbox-container input[type="checkbox"]:checked + .checkmark {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .checkbox-container input[type="checkbox"]:checked + .checkmark:after {
        opacity: 1;
    }

    .link {
        color: var(--primary-color);
        text-decoration: none;
    }

    .link:hover {
        text-decoration: underline;
    }

    .btn {
        padding: 15px 25px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
    }

    .btn-full {
        width: 100%;
    }

    .btn-auth {
        padding: 18px;
        font-size: 1.1rem;
        margin-top: 10px;
    }

    .auth-divider {
        text-align: center;
        margin: 20px 0;
        position: relative;
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
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .social-auth {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .btn-social {
        background: white;
        border: 2px solid #e0e0e0;
        color: var(--text-color);
        padding: 12px;
    }

    .btn-social:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .btn-google:hover {
        border-color: #db4437;
        color: #db4437;
    }

    .btn-facebook:hover {
        border-color: #4267B2;
        color: #4267B2;
    }

    .auth-footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .auth-footer p {
        color: var(--text-light);
    }

    .auth-footer a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
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
        justify-content: center;
    }

    .welcome-content {
        max-width: 500px;
    }

    .welcome-content h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        text-align: center;
    }

    .welcome-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 40px;
        text-align: center;
        opacity: 0.9;
    }

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
        backdrop-filter: blur(10px);
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

    .error-message {
        color: var(--danger-color);
        font-size: 0.8rem;
        margin-top: 5px;
        display: block;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .auth-container {
            grid-template-columns: 1fr;
        }
        
        .auth-card {
            padding: 30px 20px;
        }
        
        .auth-welcome {
            display: none;
        }
        
        .benefits-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-preview {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .social-auth {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 10px;
        }
        
        .auth-card {
            padding: 20px 15px;
        }
        
        .auth-header h2 {
            font-size: 1.8rem;
        }
    }
    </style>
</head>
<body>
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
                
                @if($errors->any())
                    <div style="background: #ffe6e6; color: #e74c3c; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ffcccc;">
                        <strong>Error:</strong> Por favor corrige los siguientes errores:
                        <ul style="margin: 10px 0 0 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('success'))
                    <div style="background: #e6ffe6; color: #27ae60; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ccffcc;">
                        {{ session('success') }}
                    </div>
                @endif
                
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
                            <input type="password" id="password" name="password" required placeholder="Mínimo 6 caracteres">
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
                            <input type="checkbox" name="terms" required {{ old('terms') ? 'checked' : '' }}>
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
        
        if (password.length >= 6) strength += 25;
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
        
        // Mostrar mensajes de éxito/error
        @if(session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#e74c3c'
            });
        @endif
    });
    </script>
</body>
</html>