<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WasiQhari' }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.min.css">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <a href="{{ route('home') }}" class="nav-link {{ ($page ?? '') == 'home' ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="{{ route('about') }}" class="nav-link {{ ($page ?? '') == 'about' ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i> Nosotros
                </a>
                <a href="{{ route('services') }}" class="nav-link {{ ($page ?? '') == 'services' ? 'active' : '' }}">
                    <i class="fas fa-hands-helping"></i> Servicios
                </a>
                <a href="{{ route('contact') }}" class="nav-link {{ ($page ?? '') == 'contact' ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Contacto
                </a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link {{ ($page ?? '') == 'dashboard' ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    
                    <div class="header-user">
                        <button class="btn-mode" onclick="toggleDarkMode()" title="Modo Oscuro/Claro">
                            <i class="fas fa-moon"></i>
                        </button>
                        
                        <div class="notification-bell" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="notification-count" id="notificationCount">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                            
                            <div class="notifications-panel" id="notificationsPanel">
                                <div class="notifications-header">
                                    <h4>Notificaciones</h4>
                                    <a href="{{ route('notifications.read') }}" style="font-size: 0.8rem; color: var(--primary-color); text-decoration: none;">
                                        Marcar todo leído
                                    </a>
                                </div>
                                <div class="notifications-list">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <div class="notification-item unread">
                                            <div class="notification-icon">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }}"></i>
                                            </div>
                                            <div class="notification-content">
                                                <h5>{{ $notification->data['titulo'] }}</h5>
                                                <p>{{ $notification->data['mensaje'] }}</p>
                                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="notification-item" style="justify-content: center; color: #999;">
                                            <p style="margin: 10px 0;">No tienes notificaciones nuevas</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <div class="user-menu" onclick="toggleUserMenu()">
                            <div class="user-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                            
                            <div class="user-dropdown" id="userDropdown">
                                <div class="user-info">
                                    <div class="user-avatar-large">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
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
                                    <button type="submit" class="dropdown-item logout" style="padding: 0;">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="nav-link register-btn">
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
        <div class="ai-header">
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
            </div>
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

    <script>
    // Modo Oscuro
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        const icon = document.querySelector('.btn-mode i');
        if (icon) {
            icon.className = document.body.classList.contains('dark-mode') ? 'fas fa-sun' : 'fas fa-moon';
        }
    }
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        const icon = document.querySelector('.btn-mode i');
        if (icon) icon.className = 'fas fa-sun';
    }

    // Notificaciones y Menús
    function toggleNotifications() {
        const panel = document.getElementById('notificationsPanel');
        const userDropdown = document.getElementById('userDropdown');
        if(userDropdown) userDropdown.classList.remove('active');
        if(panel) panel.classList.toggle('active');
    }
    function toggleUserMenu() {
        const panel = document.getElementById('notificationsPanel');
        const userDropdown = document.getElementById('userDropdown');
        if(panel) panel.classList.remove('active');
        if(userDropdown) userDropdown.classList.toggle('active');
    }

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(event) {
        const panel = document.getElementById('notificationsPanel');
        const userDropdown = document.getElementById('userDropdown');
        if (panel && !event.target.closest('.notification-bell') && !event.target.closest('.notifications-panel')) {
            panel.classList.remove('active');
        }
        if (userDropdown && !event.target.closest('.user-menu') && !event.target.closest('.user-dropdown')) {
            userDropdown.classList.remove('active');
        }
        if (aiPanelOpen && !event.target.closest('.ai-panel') && !event.target.closest('.dropdown-item') && !event.target.matches('.ai-option') && !event.target.closest('.ai-option')) {
            closeAIPanel();
        }
    });

    // Panel de IA
    let aiPanelOpen = false;
    function showAIPanel() {
        const aiPanel = document.getElementById('aiPanel');
        const aiOverlay = document.getElementById('aiOverlay');
        aiPanel.classList.add('active');
        aiOverlay.classList.add('active');
        aiPanelOpen = true;
        document.getElementById('userDropdown').classList.remove('active');
        document.getElementById('notificationsPanel').classList.remove('active');
        document.querySelector('.ai-options').style.display = 'grid';
        document.getElementById('aiChat').style.display = 'none';
    }
    function closeAIPanel() {
        const aiPanel = document.getElementById('aiPanel');
        const aiOverlay = document.getElementById('aiOverlay');
        aiPanel.classList.remove('active');
        aiOverlay.classList.remove('active');
        aiPanelOpen = false;
    }
    function openAIChat() {
        document.querySelector('.ai-options').style.display = 'none';
        document.getElementById('aiChat').style.display = 'block';
    }

    // =================================================================
    // ¡AQUÍ ESTÁ EL CÓDIGO DE IA REAL (NO SIMULADO)! 
    // =================================================================
    function sendAIMessage() {
        const input = document.getElementById('aiInput');
        const message = input.value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (message) {
            // 1. Mostrar mensaje del usuario
            addMessage(message, 'user');
            input.value = '';
            
            // 2. Mostrar indicador de "Escribiendo..."
            const loadingId = 'loading-' + Date.now();
            addMessage('<i class="fas fa-spinner fa-spin"></i> Pensando...', 'ai', loadingId);
            
            // 3. Llamar al Backend (AIController)
            fetch("{{ route('ai.chat.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                // Eliminar mensaje de carga
                const loadingElement = document.getElementById(loadingId);
                if(loadingElement) loadingElement.remove();

                // Mostrar respuesta real
                if(data.response) {
                    addMessage(data.response, 'ai');
                } else {
                    addMessage("Lo siento, hubo un problema con la respuesta.", 'ai');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const loadingElement = document.getElementById(loadingId);
                if(loadingElement) loadingElement.remove();
                addMessage("Error de conexión con el servidor de IA.", 'ai');
            });
        }
    }
    
    function addMessage(content, type, id = null) {
        const chat = document.getElementById('aiChatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        if(id) messageDiv.id = id;
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-${type === 'user' ? 'user' : 'robot'}"></i>
            </div>
            <div class="message-content">
                <p>${content}</p>
            </div>
        `;
        
        chat.appendChild(messageDiv);
        chat.scrollTop = chat.scrollHeight;
    }

    document.getElementById('aiInput')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { sendAIMessage(); }
    });

    // Funciones rápidas de IA (Ahora conectadas al chat real)
    function analyzeRisk() { openAIChat(); document.getElementById('aiInput').value = "Analiza el riesgo de mis beneficiarios actuales"; sendAIMessage(); }
    function generateReport() { openAIChat(); document.getElementById('aiInput').value = "¿Cómo puedo generar un reporte de impacto?"; sendAIMessage(); }
    function predictNeeds() { openAIChat(); document.getElementById('aiInput').value = "Predice las necesidades para el próximo mes basado en la temporada"; sendAIMessage(); }
    function optimizeRoutes() { openAIChat(); document.getElementById('aiInput').value = "Ayúdame a optimizar las rutas de visita"; sendAIMessage(); }

    // Navegación móvil
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    if(navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });
    }
    </script>
</body>
</html>

<style>
/* Estilos para el header mejorado */
.header-user {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-left: auto;
}

.btn-mode {
    background: none;
    border: none;
    color: var(--text-color);
    font-size: 1.1rem;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: var(--transition);
}

.btn-mode:hover {
    background: #f0f0f0;
    transform: rotate(15deg);
}

/* Notificaciones */
.notification-bell {
    position: relative;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: var(--transition);
}

.notification-bell:hover {
    background: #f0f0f0;
}

.notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.notifications-panel {
    position: absolute;
    top: 50px;
    right: 0;
    width: 350px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    display: none;
    z-index: 1000;
    border: 1px solid #e0e0e0;
}

.notifications-panel.active {
    display: block;
    animation: slideDown 0.3s ease;
}

.notifications-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notifications-header h4 {
    margin: 0;
    color: var(--dark-color);
    font-size: 1rem;
}

.notifications-header button {
    background: none;
    border: none;
    color: var(--primary-color);
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 500;
}

.notifications-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 15px;
    border-bottom: 1px solid #f5f5f5;
    transition: var(--transition);
}

.notification-item:hover {
    background: #f8f9fa;
}

.notification-item.unread {
    background: #f0f7ff;
    border-left: 3px solid var(--primary-color);
}

.notification-icon {
    font-size: 1rem;
    margin-top: 2px;
    min-width: 20px;
}

.notification-content h5 {
    margin: 0 0 4px 0;
    font-size: 0.9rem;
    color: var(--dark-color);
}

.notification-content p {
    margin: 0 0 4px 0;
    font-size: 0.8rem;
    color: var(--text-color);
    line-height: 1.3;
}

.notification-content small {
    color: var(--text-light);
    font-size: 0.7rem;
}

/* Menú de usuario */
.user-menu {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 20px;
    transition: var(--transition);
    position: relative;
}

.user-menu:hover {
    background: #f0f0f0;
}

.user-avatar {
    width: 32px;
    height: 32px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.user-name {
    font-weight: 500;
    color: var(--dark-color);
    font-size: 0.9rem;
}

.user-dropdown {
    position: absolute;
    top: 50px;
    right: 0;
    width: 280px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    display: none;
    z-index: 1000;
    border: 1px solid #e0e0e0;
}

.user-dropdown.active {
    display: block;
    animation: slideDown 0.3s ease;
}

.user-info {
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
}

.user-avatar-large {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.user-details {
    flex: 1;
}

.user-details strong {
    display: block;
    margin-bottom: 2px;
    font-size: 1rem;
}

.user-details span {
    font-size: 0.8rem;
    opacity: 0.9;
    display: block;
    margin-bottom: 2px;
}

.user-details small {
    font-size: 0.7rem;
    opacity: 0.8;
}

.dropdown-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 5px 0;
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
    font-size: 0.9rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: var(--primary-color);
}

.dropdown-item.logout {
    color: #e74c3c;
}

.dropdown-item.logout:hover {
    background: #ffebee;
}

/* Panel de IA - POSICIÓN MEJORADA */
.ai-panel {
    position: fixed;
    top: 120px; /* Distancia fija desde el top */
    left: 50%;
    transform: translateX(-50%) scale(0.9);
    width: 90%;
    max-width: 500px;
    max-height: 80vh;
    background: white;
    border-radius: 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    z-index: 10000;
    display: none;
    overflow: hidden;
}

.ai-panel.active {
    display: block;
    animation: modalSlideDown 0.3s ease forwards;
}

@keyframes modalSlideDown {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0) scale(1);
    }
}

.ai-header {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ai-header h4 {
    margin: 0;
    font-size: 1.2rem;
}

.btn-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: var(--transition);
}

.btn-close:hover {
    background: rgba(255,255,255,0.2);
}

.ai-content {
    padding: 20px;
}

.ai-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.ai-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 20px 15px;
    background: #f8f9fa;
    border-radius: 10px;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
}

.ai-option:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.ai-option i {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.ai-option span {
    font-size: 0.8rem;
    font-weight: 500;
}

.ai-chat {
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
}

.chat-messages {
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 15px;
}

.message {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.message-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.user-message .message-avatar {
    background: var(--primary-color);
    color: white;
}

.ai-message .message-avatar {
    background: #f39c12;
    color: white;
}

.message-content {
    flex: 1;
}

.message-content p {
    margin: 0;
    padding: 10px 15px;
    border-radius: 15px;
    font-size: 0.9rem;
    line-height: 1.4;
}

.user-message .message-content p {
    background: var(--primary-color);
    color: white;
    border-bottom-right-radius: 5px;
}

.ai-message .message-content p {
    background: #f8f9fa;
    color: var(--text-color);
    border-bottom-left-radius: 5px;
}

.chat-input {
    display: flex;
    gap: 10px;
}

.chat-input input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    outline: none;
    font-size: 0.9rem;
}

.chat-input button {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
    transition: var(--transition);
}

.chat-input button:hover {
    background: #2980b9;
    transform: scale(1.05);
}

/* ===== MODO OSCURO MEJORADO ===== */
body.dark-mode {
    background: #121212;
    color: #e0e0e0;
}

body.dark-mode .navbar {
    background: #1e1e1e;
    border-bottom: 1px solid #333;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

body.dark-mode .nav-link {
    color: #e0e0e0;
}

body.dark-mode .nav-link:hover,
body.dark-mode .nav-link.active {
    color: var(--primary-color);
    background: rgba(102, 126, 234, 0.1);
}

body.dark-mode .nav-logo a {
    color: #e0e0e0;
}

body.dark-mode .nav-toggle span {
    background: #e0e0e0;
}

/* Cards y contenedores en modo oscuro */
body.dark-mode .profile-card,
body.dark-mode .settings-card,
body.dark-mode .dashboard-card {
    background: #1e1e1e;
    border: 1px solid #333;
    color: #e0e0e0;
}

body.dark-mode .profile-card h2,
body.dark-mode .profile-card h3,
body.dark-mode .settings-card h3 {
    color: #ffffff;
}

body.dark-mode .form-control {
    background: #2d2d2d;
    border: 1px solid #444;
    color: #e0e0e0;
}

body.dark-mode .form-control:focus {
    border-color: var(--primary-color);
    background: #333;
}

body.dark-mode .stat,
body.dark-mode .impact-item,
body.dark-mode .ai-option {
    background: #2d2d2d;
    color: #e0e0e0;
}

body.dark-mode .stat:hover,
body.dark-mode .impact-item:hover {
    background: var(--primary-color);
    color: white;
}

/* Menús desplegables en modo oscuro */
body.dark-mode .notifications-panel,
body.dark-mode .user-dropdown,
body.dark-mode .ai-panel {
    background: #1e1e1e;
    border: 1px solid #333;
    color: #e0e0e0;
}

body.dark-mode .notifications-header,
body.dark-mode .ai-header {
    background: linear-gradient(135deg, #5561c3 0%, #6a4a8c 100%);
    border-bottom: 1px solid #333;
}

body.dark-mode .notification-item {
    border-bottom-color: #333;
}

body.dark-mode .notification-item:hover {
    background: #2d2d2d;
}

body.dark-mode .notification-item.unread {
    background: #1a2a3a;
    border-left-color: var(--primary-color);
}

body.dark-mode .dropdown-item:hover {
    background: #2d2d2d;
}

body.dark-mode .dropdown-divider {
    background: #333;
}

/* Botones en modo oscuro */
body.dark-mode .btn-primary {
    background: var(--primary-color);
    color: white;
}

body.dark-mode .btn-mode:hover,
body.dark-mode .notification-bell:hover,
body.dark-mode .user-menu:hover {
    background: #333;
}

/* Switch en modo oscuro */
body.dark-mode .slider {
    background: #555;
}

body.dark-mode input:checked + .slider {
    background: var(--primary-color);
}

/* Chat IA en modo oscuro */
body.dark-mode .ai-message .message-content p {
    background: #2d2d2d;
    color: #e0e0e0;
}

body.dark-mode .chat-input input {
    background: #2d2d2d;
    border-color: #444;
    color: #e0e0e0;
}

/* Textos específicos */
body.dark-mode .profile-info p,
body.dark-mode .setting-info p,
body.dark-mode .impact-item span {
    color: #b0b0b0;
}

/* Tablas en modo oscuro (si las tienes) */
body.dark-mode table {
    background: #1e1e1e;
    color: #e0e0e0;
}

body.dark-mode th {
    background: #2d2d2d;
    color: #ffffff;
}

body.dark-mode td {
    border-color: #333;
}

body.dark-mode tr:hover {
    background: #2d2d2d;
}

/* Animaciones */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .header-user {
        gap: 10px;
    }
    
    .user-name {
        display: none;
    }
    
    .notifications-panel {
        width: 300px;
        right: -50px;
    }
    
    .user-dropdown {
        width: 250px;
    }
    
    .ai-panel {
        width: 95%;
    }
    
    .ai-options {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .notifications-panel {
        width: 280px;
        right: -80px;
    }
    
    .user-dropdown {
        width: 220px;
    }
}

/* Agrega esto en la sección de estilos del panel de IA */
.ai-chat {
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
    max-height: 400px;
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex: 1;
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 15px;
    padding: 10px;
}

.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Mejorar la apariencia de los mensajes */
.message-content p {
    margin: 0;
    padding: 12px 15px;
    border-radius: 18px;
    font-size: 0.9rem;
    line-height: 1.4;
    white-space: pre-line;
}

.user-message {
    justify-content: flex-end;
}

.user-message .message-content p {
    background: var(--primary-color);
    color: white;
    border-bottom-right-radius: 5px;
}

.ai-message {
    justify-content: flex-start;
}

.ai-message .message-content p {
    background: #f8f9fa;
    color: var(--text-color);
    border-bottom-left-radius: 5px;
}

/* Modo oscuro para el chat */
body.dark-mode .ai-message .message-content p {
    background: #2d2d2d;
    color: #e0e0e0;
}

body.dark-mode .chat-messages::-webkit-scrollbar-track {
    background: #2d2d2d;
}

body.dark-mode .chat-messages::-webkit-scrollbar-thumb {
    background: #555;
}
</style>