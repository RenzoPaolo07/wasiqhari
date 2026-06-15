<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WasiQhari Dashboard')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    
    <!-- Bootstrap CSS (para los estilos de alerta) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    @stack('styles')
</head>
<body>
    <button id="mobileMenuBtn" class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>

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
                <a href="{{ route('inventario') }}" class="nav-link {{ ($page ?? '') == 'inventario' ? 'active' : '' }}">
                    <i class="fas fa-box-open"></i>
                    <span>Inventario</span>
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
            <!-- Contenedor de alertas de emergencia -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div id="alertas-container"></div>
                    </div>
                </div>
                <!-- Resto del contenido del dashboard -->
                @yield('content')
            </div>
        </main>

    </div> 
    
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registrado! Scope:', reg.scope))
                    .catch(err => console.log('Error SW:', err));
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobileMenuBtn');
            const sidebar = document.querySelector('.sidebar');
            
            if(btn && sidebar) {
                // Abrir/Cerrar menú al tocar el botón
                btn.addEventListener('click', function(e) {
                    e.stopPropagation(); // Evita que el clic se propague al documento
                    sidebar.classList.toggle('active');
                });

                // Cerrar menú al tocar fuera de él
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        // Si el clic NO fue en el sidebar Y NO fue en el botón
                        if (!sidebar.contains(e.target) && !btn.contains(e.target) && sidebar.classList.contains('active')) {
                            sidebar.classList.remove('active');
                        }
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')

    @push('scripts')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Sonido de alerta
        const audio = new Audio('/sounds/alerta.mp3');
        
        // Variable para evitar alertas duplicadas
        let ultimasAlertas = new Set();
        
        // Función para mostrar alerta
        function mostrarAlerta(data) {
            // Verificar si ya mostramos esta alerta (evitar duplicados)
            const alertaId = `${data.paciente}_${data.tipo_alerta}_${Date.now()}`;
            if (ultimasAlertas.has(alertaId)) return;
            ultimasAlertas.add(alertaId);
            
            // Limpiar alertas antiguas después de 5 segundos
            setTimeout(() => ultimasAlertas.delete(alertaId), 5000);
            
            const alertaHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideInRight 0.5s;">
                    <strong>🚨 EMERGENCIA!</strong><br>
                    <strong>Paciente:</strong> ${data.paciente}<br>
                    <strong>Tipo:</strong> ${data.tipo_alerta}<br>
                    <strong>Fuerza:</strong> ${data.fuerza_g} G<br>
                    <small>${new Date().toLocaleTimeString()}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            $('#alertas-container').prepend(alertaHTML);
            
            // Reproducir sonido
            audio.play().catch(e => console.log('Error reproduciendo sonido:', e));
            
            // Mostrar notificación del sistema
            if (Notification.permission === "granted") {
                new Notification("🚨 Wasiqhari - Emergencia", {
                    body: `${data.paciente} - ${data.tipo_alerta}`,
                    icon: "/icon-alerta.png",
                    requireInteraction: true
                });
            }
            
            // Opcional: Mostrar SweetAlert si la alerta es crítica
            if (data.tipo_alerta === 'caida' || data.fuerza_g > 2.5) {
                Swal.fire({
                    title: '🚨 ALERTA DE EMERGENCIA',
                    html: `<strong>Paciente:</strong> ${data.paciente}<br>
                           <strong>Tipo:</strong> ${data.tipo_alerta}<br>
                           <strong>Fuerza:</strong> ${data.fuerza_g} G`,
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendido',
                    background: '#fff5f5',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    }
                });
            }
        }
        
        // Solicitar permiso para notificaciones
        if (Notification.permission !== "denied") {
            Notification.requestPermission();
        }
        
        // Polling cada 3 segundos (alternativa a WebSockets)
        let ultimoId = 0;
        
        setInterval(() => {
            fetch('/api/ultimas-emergencias')
                .then(res => {
                    if (!res.ok) throw new Error('Error en la respuesta');
                    return res.json();
                })
                .then(data => {
                    if (data && Array.isArray(data)) {
                        data.forEach(alerta => {
                            if (alerta.id && alerta.id > ultimoId) {
                                mostrarAlerta(alerta);
                                ultimoId = alerta.id;
                            }
                        });
                    }
                })
                .catch(err => console.log('Error polling emergencias:', err));
        }, 3000);
        
        // WebSockets con Pusher (opcional, más rápido)
        // Descomentar si tienes Pusher configurado
        /*
        const pusher = new Pusher('TU_APP_KEY_PUSHER', {
            cluster: 'TU_CLUSTER',
            encrypted: true
        });
        
        const channel = pusher.subscribe('emergencias');
        channel.bind('nueva-emergencia', function(data) {
            mostrarAlerta(data);
        });
        */
        
        // Estilos CSS dinámicos para la animación
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            #alertas-container .alert {
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                border-left: 5px solid #ff0000;
                animation: slideInRight 0.3s ease-out;
            }
            
            #alertas-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                max-width: 400px;
                width: calc(100% - 40px);
            }
            
            @media (max-width: 768px) {
                #alertas-container {
                    top: 10px;
                    right: 10px;
                    left: 10px;
                    max-width: none;
                    width: auto;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @endpush
</body>
</html>