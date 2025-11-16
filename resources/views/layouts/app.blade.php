<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', 'WasiQhari')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.min.css">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="{{ route('home') }}">
                    <i class="fas fa-heart"></i>
                    <span>WasiQhari</span>
                </a>
            </div>
            
            <div class="nav-menu" id="navMenu">
                <a href="{{ route('home') }}" class="nav-link {{ (request()->routeIs('home')) ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="{{ route('about') }}" class="nav-link {{ (request()->routeIs('about')) ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i> Nosotros
                </a>
                <a href="{{ route('services') }}" class="nav-link {{ (request()->routeIs('services')) ? 'active' : '' }}">
                    <i class="fas fa-hands-helping"></i> Servicios
                </a>
                <a href="{{ route('contact') }}" class="nav-link {{ (request()->routeIs('contact')) ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Contacto
                </a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link {{ (request()->routeIs('dashboard*')) ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    
                    <div class="header-user">
                        <button class="btn-mode" onclick="toggleDarkMode()" title="Modo Oscuro/Claro">
                            <i class="fas fa-moon"></i>
                        </button>
                        
                        <div class="notification-bell" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count" id="notificationCount">3</span>
                            
                            <div class="notifications-panel" id="notificationsPanel">
                                <div class="notifications-header">
                                    <h4>Notificaciones</h4>
                                    <button onclick="markAllAsRead()">Marcar todo leído</button>
                                </div>
                                <div class="notifications-list">
                                    <div class="notification-item unread">
                                        <div class="notification-icon">
                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                        </div>
                                        <div class="notification-content">
                                            <h5>Alerta de Salud</h5>
                                            <p>Martina Quispe requiere atención médica urgente</p>
                                            <small>Hace 2 horas</small>
                                        </div>
                                    </div>
                                    <div class="notification-item unread">
                                        <div class="notification-icon">
                                            <i class="fas fa-calendar-check text-warning"></i>
                                        </div>
                                        <div class="notification-content">
                                            <h5>Visita Pendiente</h5>
                                            <p>Tienes una visita programada para hoy</p>
                                            <small>Hace 5 horas</small>
                                        </div>
                                    </div>
                                    <div class="notification-item">
                                        <div class="notification-icon">
                                            <i class="fas fa-user-plus text-success"></i>
                                        </div>
                                        <div class="notification-content">
                                            <h5>Nuevo Voluntario</h5>
                                            <p>Se ha registrado un nuevo voluntario en tu zona</p>
                                            <small>Ayer</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="user-menu" onclick="toggleUserMenu()">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                            
                            <div class="user-dropdown" id="userDropdown">
                                <div class="user-info">
                                    <div class="user-avatar-large">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <strong>{{ Auth::user()->name }}</strong>
                                        <span>{{ Auth::user()->email }}</span>
                                        <small>Rol: {{ ucfirst(Auth::user()->role) }}</small>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('profile') }}" class="dropdown-item">
                                    <i class="fas fa-user-cog"></i> Mi Perfil
                                </a>
                                <a href="{{ route('settings') }}" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Configuración
                                </a>
                                <a href="{{ route('ai') }}" class="dropdown-item">
                                    <i class="fas fa-robot"></i> Análisis IA
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" class="dropdown-item logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                @else
                    <a href="{{ route('login') }}" class="nav-link {{ (request()->routeIs('login')) ? 'active' : '' }}">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="nav-link register-btn {{ (request()->routeIs('register')) ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </a>
                @endauth
            </div>
            
            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <div class="ai-overlay" id="aiOverlay" onclick="closeAIPanel()"></div>
    <div class="ai-panel" id="aiPanel">
        <!--<div class="ai-header">
            <h4>🤖 Asistente IA WasiQhari</h4>
            <button class="btn-close" onclick="closeAIPanel()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="ai-content">
            <div class="ai-options">
                <div class="ai-option" onclick="analyzeRisk()">
                    <i class="fas fa-chart-line"></i>
                    <span>Analizar Riesgo</span>
                </div>
                <div class="ai-option" onclick="generateReport()">
                    <i class="fas fa-file-alt"></i>
                    <span>Generar Reporte</span>
                </div>
                <div class="ai-option" onclick="predictNeeds()">
                    <i class="fas fa-crystal-ball"></i>
                    <span>Predecir Necesidades</span>
                </div>
                <div class="ai-option" onclick="optimizeRoutes()">
                    <i class="fas fa-route"></i>
                    <span>Optimizar Rutas</span>
                </div>
            </div>-->
            <div class="ai-chat" id="aiChat" style="display: none;">
                <div class="chat-messages" id="aiChatMessages">
                    <div class="message ai-message">
                        <div class="message-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="message-content">
                            <p>¡Hola! Soy tu asistente IA. ¿En qué puedo ayudarte hoy?</p>
                        </div>
                    </div>
                </div>
                <div class="chat-input">
                    <input type="text" id="aiInput" placeholder="Escribe tu pregunta...">
                    <button onclick="sendAIMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <main>
        @yield('content')
    </main>
    @include('footer')


    <script>
    // Modo Oscuro
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        
        // Cambiar icono
        const icon = document.querySelector('.btn-mode i');
        if (document.body.classList.contains('dark-mode')) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    }

    // Cargar modo oscuro si estaba activo
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        const icon = document.querySelector('.btn-mode i');
        if (icon) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    }

    // Notificaciones
    function toggleNotifications() {
        const panel = document.getElementById('notificationsPanel');
        const isActive = panel.classList.contains('active');
        
        // Cerrar otros menús
        document.getElementById('userDropdown').classList.remove('active');
        document.getElementById('aiPanel').classList.remove('active');
        
        // Toggle notificaciones
        panel.classList.toggle('active', !isActive);
    }

    function markAllAsRead() {
        const notifications = document.querySelectorAll('.notification-item.unread');
        notifications.forEach(notif => {
            notif.classList.remove('unread');
        });
        document.getElementById('notificationCount').textContent = '0';
    }

    // Menú de usuario
    function toggleUserMenu() {
        const dropdown = document.getElementById('userDropdown');
        const isActive = dropdown.classList.contains('active');
        
        // Cerrar otros menús
        document.getElementById('notificationsPanel').classList.remove('active');
        document.getElementById('aiPanel').classList.remove('active');
        
        // Toggle menú usuario
        dropdown.classList.toggle('active', !isActive);
    }

    // Panel de IA - MEJORADO CON OVERLAY
    let aiPanelOpen = false;

    function showAIPanel() {
        const aiPanel = document.getElementById('aiPanel');
        const aiOverlay = document.getElementById('aiOverlay');
        
        aiPanel.classList.add('active');
        aiOverlay.classList.add('active');
        aiPanelOpen = true;
        
        // Cerrar otros menús
        document.getElementById('userDropdown').classList.remove('active');
        document.getElementById('notificationsPanel').classList.remove('active');
        
        // Mostrar las opciones principales y ocultar el chat
        document.querySelector('.ai-options').style.display = 'grid';
        document.getElementById('aiChat').style.display = 'none';
        
        // Limpiar el chat
        document.getElementById('aiChatMessages').innerHTML = `
            <div class="message ai-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <p>¡Hola! Soy tu asistente IA. ¿En qué puedo ayudarte hoy?</p>
                </div>
            </div>
        `;
    }

    function closeAIPanel() {
        const aiPanel = document.getElementById('aiPanel');
        const aiOverlay = document.getElementById('aiOverlay');
        
        aiPanel.classList.remove('active');
        aiOverlay.classList.remove('active');
        aiPanelOpen = false;
    }

    // Cerrar con Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && aiPanelOpen) {
            closeAIPanel();
        }
    });

    // Cerrar menús al hacer clic fuera - MEJORADO
    document.addEventListener('click', function(event) {
        const aiPanel = document.getElementById('aiPanel');
        const notificationsPanel = document.getElementById('notificationsPanel');
        const userDropdown = document.getElementById('userDropdown');
        
        // Solo cerrar si se hace clic fuera de los contenedores
        if (!event.target.closest('.notification-bell') && !event.target.closest('.notifications-panel')) {
            notificationsPanel.classList.remove('active');
        }
        
        if (!event.target.closest('.user-menu') && !event.target.closest('.user-dropdown')) {
            userDropdown.classList.remove('active');
        }
        
        // Para el panel de IA, ser más específico
        if (aiPanelOpen && 
            !event.target.closest('.ai-panel') && 
            !event.target.closest('.dropdown-item') &&
            !event.target.matches('.ai-option') &&
            !event.target.closest('.ai-option')) {
            closeAIPanel();
        }
    });

    // Función para abrir el chat IA
    function openAIChat() {
        document.querySelector('.ai-options').style.display = 'none';
        document.getElementById('aiChat').style.display = 'block';
    }

    // Funciones de IA - CORREGIDAS
    function analyzeRisk() {
        console.log('Analizando riesgo...');
        openAIChat();
        addMessage('Iniciando análisis de riesgo para adultos mayores...', 'user');
        
        setTimeout(() => {
            addMessage('He analizado los datos y encontré 12 casos de alto riesgo. Los adultos mayores en la zona de Cusco centro requieren atención inmediata. ¿Te gustaría que genere un plan de acción detallado?', 'ai');
        }, 1500);
    }

    function generateReport() {
        console.log('Generando reporte...');
        openAIChat();
        addMessage('Solicitando generación de reporte completo...', 'user');
        
        setTimeout(() => {
            addMessage('Puedo generar varios tipos de reportes:\n• Reporte de riesgo mensual\n• Análisis de impacto social\n• Desempeño de voluntarios\n\n¿Sobre qué área te gustaría el reporte?', 'ai');
        }, 1500);
    }

    function predictNeeds() {
        console.log('Prediciendo necesidades...');
        openAIChat();
        addMessage('Analizando necesidades futuras...', 'user');
        
        setTimeout(() => {
            addMessage('Basado en patrones históricos, predigo que necesitarás:\n• +15% de alimentos el próximo mes\n• 8 casos requerirán medicación constante\n• 20+ abrigos para temporada de frío\n\n¿Quieres que detalle las acciones recomendadas?', 'ai');
        }, 1500);
    }

    function optimizeRoutes() {
        console.log('Optimizando rutas...');
        openAIChat();
        addMessage('Optimizando rutas de visitas...', 'user');
        
        setTimeout(() => {
            addMessage('He optimizado las rutas! Resultados:\n• 35% más eficiente\n• 2.5 horas ahorradas por voluntario\n• 15% menos combustible\n\n¿Deseas ver el mapa con las nuevas rutas?', 'ai');
        }, 1500);
    }

    // Chat IA mejorado
    function sendAIMessage() {
        const input = document.getElementById('aiInput');
        const message = input.value.trim();
        
        if (message) {
            addMessage(message, 'user');
            input.value = '';
            
            // Simular procesamiento IA
            setTimeout(() => {
                let response = '';
                
                if (message.toLowerCase().includes('hola') || message.toLowerCase().includes('hi')) {
                    response = "¡Hola! Soy tu asistente IA de WasiQhari. Puedo ayudarte con análisis de riesgo, reportes, predicciones y optimización de rutas. ¿En qué necesitas ayuda?";
                } else if (message.toLowerCase().includes('riesgo') || message.toLowerCase().includes('peligro')) {
                    response = "Actualmente tenemos 12 casos de alto riesgo identificados. Los factores principales son: edad avanzada (85+), condiciones médicas crónicas y falta de apoyo familiar. ¿Quieres que detalle los casos específicos?";
                } else if (message.toLowerCase().includes('reporte') || message.toLowerCase().includes('informe')) {
                    response = "Puedo generar reportes en PDF o Excel. Los tipos disponibles son: mensual, trimestral, anual, por zona geográfica, o por tipo de servicio. ¿Cuál necesitas?";
                } else if (message.toLowerCase().includes('necesidad') || message.toLowerCase().includes('ayuda')) {
                    response = "Basado en el análisis predictivo, las necesidades más urgentes son: alimentos no perecederos, medicamentos para presión y diabetes, y abrigos para el invierno. ¿Necesitas la lista completa?";
                } else {
                    response = "Entiendo que necesitas ayuda. Como asistente IA de WasiQhari, puedo analizar datos de adultos mayores, generar reportes, predecir necesidades y optimizar rutas. ¿En qué aspecto específico te puedo apoyar?";
                }
                
                addMessage(response, 'ai');
            }, 1000 + Math.random() * 1000);
        }
    }

    // Función auxiliar para agregar mensajes
    function addMessage(content, type) {
        const chat = document.getElementById('aiChatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-${type === 'user' ? 'user' : 'robot'}"></i>
            </div>
            <div class="message-content">
                <p>${content.replace(/\n/g, '<br>')}</p>
            </div>
        `;
        
        chat.appendChild(messageDiv);
        chat.scrollTop = chat.scrollHeight;
    }

    // Permitir enviar mensaje con Enter
    document.getElementById('aiInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendAIMessage();
        }
    });

    // Navegación móvil
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    navToggle?.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        navToggle.classList.toggle('active');
    });

    // Cerrar menú al hacer clic en un enlace
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            navToggle.classList.remove('active');
        });
    });
    </script>
    
    @stack('scripts')
</body>
</html>