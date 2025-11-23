<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WasiQhari Dashboard')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--Cargar pagina movil-->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#e74c3c">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/1077/1077114.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="dashboard-layout">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('home') }}" class="nav-logo">
                    <i class="fas fa-heart"></i>
                    <span>WasiQhari</span>
                </a>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ ($page ?? '') == 'dashboard' ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('calendario') }}" class="nav-link {{ ($page ?? '') == 'calendario' ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendario</span>
                </a>
                <a href="{{ route('adultos') }}" class="nav-link {{ ($page ?? '') == 'adultos' ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Adultos Mayores</span>
                </a>
                <a href="{{ route('voluntarios') }}" class="nav-link {{ ($page ?? '') == 'voluntarios' ? 'active' : '' }}">
                    <i class="fas fa-hands-helping"></i>
                    <span>Voluntarios</span>
                </a>
                <a href="{{ route('visitas') }}" class="nav-link {{ ($page ?? '') == 'visitas' ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Visitas</span>
                </a>
                <a href="{{ route('reportes') }}" class="nav-link {{ ($page ?? '') == 'reportes' ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Reportes</span>
                </a>
                <a href="{{ route('ai') }}" class="nav-link {{ ($page ?? '') == 'ai' ? 'active' : '' }}">
                    <i class="fas fa-robot"></i>
                    <span>Análisis IA</span>
                </a>
                <a href="{{ route('auditoria') }}" class="nav-link {{ ($page ?? '') == 'auditoria' ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Auditoría</span>
                </a>
                <a href="{{ route('settings') }}" class="nav-link {{ ($page ?? '') == 'settings' ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="{{ route('profile') }}" class="nav-link profile-link">
                    <i class="fas fa-user"></i>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>

    </div> <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Movil-->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registrado! Scope:', reg.scope))
                    .catch(err => console.log('Error SW:', err));
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>