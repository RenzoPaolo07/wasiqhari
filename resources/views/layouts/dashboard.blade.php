<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WasiQhari')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    <!-- Header Público (Inicio, Nosotros, etc.) -->
    <header class="public-header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo">
                    <i class="fas fa-heart"></i>
                    <span>WasiQhari</span>
                </div>
                <nav class="main-nav">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                    <a href="{{ route('about') }}" class="nav-link">
                        <i class="fas fa-info-circle"></i> Nosotros
                    </a>
                    <a href="{{ route('services') }}" class="nav-link">
                        <i class="fas fa-hand-holding-heart"></i> Servicios
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link">
                        <i class="fas fa-envelope"></i> Contacto
                    </a>
                </nav>
            </div>
            
            <div class="header-right">
                @auth
                    <div class="user-menu">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">{{ Auth::user()->role }}</span>
                        </div>
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                @else
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-outline">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Header del Dashboard (Navegación interna) -->
    <header class="dashboard-nav">
        <div class="dashboard-nav-content">
            <nav class="dashboard-menu">
                <a href="{{ route('dashboard') }}" class="dashboard-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('adultos') }}" class="dashboard-nav-link {{ request()->routeIs('adultos') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Adultos Mayores
                </a>
                <a href="{{ route('voluntarios') }}" class="dashboard-nav-link {{ request()->routeIs('voluntarios') ? 'active' : '' }}">
                    <i class="fas fa-hands-helping"></i> Voluntarios
                </a>
                <a href="{{ route('visitas') }}" class="dashboard-nav-link {{ request()->routeIs('visitas') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Visitas
                </a>
                <a href="{{ route('ai') }}" class="dashboard-nav-link {{ request()->routeIs('ai') ? 'active' : '' }}">
                    <i class="fas fa-robot"></i> IA
                </a>
                <a href="{{ route('reporters') }}" class="dashboard-nav-link {{ request()->routeIs('reporters') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
                <a href="{{ route('settings') }}" class="dashboard-nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Configuración
                </a>
            </nav>
            
            @auth
            <div class="dashboard-user-menu">
                <div class="user-dropdown">
                    <div class="user-avatar-sm">
                        <i class="fas fa-user"></i>
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                    
                    <div class="user-dropdown-menu">
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                        <a href="{{ route('settings') }}" class="dropdown-item">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="dropdown-item">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="dashboard-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>

<style>
:root {
    --primary-color: #e74c3c;
    --secondary-color: #c0392b;
    --dark-color: #2c3e50;
    --text-color: #34495e;
    --text-light: #7f8c8d;
    --background-color: #f8f9fa;
    --header-height: 70px;
    --dashboard-nav-height: 60px;
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
    background-color: var(--background-color);
    color: var(--text-color);
    line-height: 1.6;
}

/* Header Público */
.public-header {
    background: white;
    height: var(--header-height);
    box-shadow: var(--shadow);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.public-header .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0 30px;
    max-width: 1400px;
    margin: 0 auto;
}

.public-header .header-left {
    display: flex;
    align-items: center;
    gap: 40px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: bold;
}

.logo i {
    font-size: 2rem;
}

.public-header .main-nav {
    display: flex;
    gap: 25px;
}

.public-header .nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: var(--text-color);
    padding: 10px 15px;
    border-radius: 8px;
    transition: var(--transition);
    font-weight: 500;
}

.public-header .nav-link:hover {
    background: #f8f9fa;
    color: var(--primary-color);
}

.public-header .header-right {
    display: flex;
    align-items: center;
}

.auth-buttons {
    display: flex;
    gap: 15px;
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
}

/* Dashboard Navigation */
.dashboard-nav {
    background: white;
    height: var(--dashboard-nav-height);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: fixed;
    top: var(--header-height);
    left: 0;
    right: 0;
    z-index: 999;
    border-top: 1px solid #f0f0f0;
}

.dashboard-nav-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0 30px;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-menu {
    display: flex;
    gap: 20px;
    height: 100%;
}

.dashboard-nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: var(--text-color);
    padding: 0 20px;
    border-radius: 0;
    transition: var(--transition);
    font-weight: 500;
    height: 100%;
    border-bottom: 3px solid transparent;
}

.dashboard-nav-link:hover {
    color: var(--primary-color);
    background: #f8f9fa;
}

.dashboard-nav-link.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
    background: #f8f9fa;
}

.dashboard-user-menu {
    display: flex;
    align-items: center;
}

.user-dropdown {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    padding: 8px 15px;
    border-radius: 8px;
    transition: var(--transition);
}

.user-dropdown:hover {
    background: #f8f9fa;
}

.user-dropdown:hover .user-dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.user-avatar-sm {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.user-dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 10px;
    box-shadow: var(--shadow);
    padding: 10px 0;
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition);
    z-index: 1000;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    text-decoration: none;
    color: var(--text-color);
    transition: var(--transition);
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 1rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: var(--primary-color);
}

.btn-logout {
    background: none;
    border: none;
    color: var(--text-color);
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    text-align: left;
}

.dropdown-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 5px 0;
}

/* Main Content */
.dashboard-main {
    margin-top: calc(var(--header-height) + var(--dashboard-nav-height));
    min-height: calc(100vh - var(--header-height) - var(--dashboard-nav-height));
    padding: 30px;
    max-width: 1400px;
    margin-left: auto;
    margin-right: auto;
}

/* Botones generales */
.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.9rem;
}

.btn-full {
    width: 100%;
}

/* Responsive */
@media (max-width: 768px) {
    .public-header .header-content,
    .dashboard-nav-content {
        padding: 0 15px;
    }
    
    .public-header .main-nav {
        display: none;
    }
    
    .dashboard-menu {
        overflow-x: auto;
        gap: 10px;
    }
    
    .dashboard-nav-link {
        padding: 0 15px;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    
    .dashboard-main {
        padding: 20px 15px;
    }
    
    .user-dropdown span {
        display: none;
    }
}

@media (max-width: 480px) {
    .dashboard-main {
        padding: 15px 10px;
    }
    
    .auth-buttons {
        gap: 10px;
    }
    
    .btn-outline,
    .btn-primary {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
}
</style>