<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WasiQhari - Bienvenido</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-content">
            <div class="welcome-logo">
                <i class="fas fa-heart"></i>
                <h1>WasiQhari</h1>
            </div>
            <p class="welcome-subtitle">Red de apoyo y monitoreo social</p>
            <p class="welcome-description">
                Conectamos a adultos mayores en situación de vulnerabilidad con voluntarios 
                y organizaciones solidarias para construir una comunidad más unida.
            </p>
            
            <div class="welcome-actions">
                @if (Route::has('login'))
                    <div class="auth-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt"></i> Ir al Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-secondary">
                                    <i class="fas fa-user-plus"></i> Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
                
                <div class="quick-links">
                    <a href="{{ route('about') }}" class="link">
                        <i class="fas fa-info-circle"></i> Conoce más sobre nosotros
                    </a>
                    <a href="{{ route('services') }}" class="link">
                        <i class="fas fa-hands-helping"></i> Nuestros servicios
                    </a>
                </div>
            </div>
        </div>
        
        <div class="welcome-footer">
            <p>&copy; 2025 WasiQhari. Desarrollado con <i class="fas fa-heart"></i> por estudiantes de la Universidad Continental</p>
        </div>
    </div>

    <style>
    .welcome-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
        padding: 2rem;
    }

    .welcome-content {
        max-width: 500px;
        width: 100%;
    }

    .welcome-logo {
        margin-bottom: 1rem;
    }

    .welcome-logo i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #ff6b6b;
    }

    .welcome-logo h1 {
        font-size: 3rem;
        margin: 0;
        font-weight: 700;
    }

    .welcome-subtitle {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .welcome-description {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2.5rem;
        opacity: 0.8;
    }

    .auth-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #ff6b6b;
        color: white;
        border: 2px solid #ff6b6b;
    }

    .btn-primary:hover {
        background: transparent;
        border-color: white;
    }

    .btn-secondary {
        background: transparent;
        color: white;
        border: 2px solid white;
    }

    .btn-secondary:hover {
        background: white;
        color: #667eea;
    }

    .quick-links {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .link {
        color: white;
        text-decoration: none;
        opacity: 0.8;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .link:hover {
        opacity: 1;
    }

    .welcome-footer {
        margin-top: 3rem;
        opacity: 0.7;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .auth-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn {
            width: 200px;
            justify-content: center;
        }
        
        .welcome-logo h1 {
            font-size: 2.5rem;
        }
    }
    </style>
</body>
</html>