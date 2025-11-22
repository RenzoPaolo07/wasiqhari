<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WasiQhari' }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Driver.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.min.css">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.min.js"></script>
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navegación -->
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
                    
                    <!-- Menú de usuario con notificaciones -->
                    <div class="header-user">
                        <!-- Botón Modo Oscuro -->
                        <button class="btn-mode" onclick="toggleDarkMode()" title="Modo Oscuro/Claro">
                            <i class="fas fa-moon"></i>
                        </button>
                        
                        <!-- Campana de notificaciones -->
                        <div class="notification-bell" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count" id="notificationCount">3</span>
                            
                            <!-- Panel de notificaciones -->
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
                        
                        <!-- Menú de usuario -->
                        <div class="user-menu" onclick="toggleUserMenu()">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                            
                            <!-- Submenú de usuario -->
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

    <!-- Overlay para panel de IA -->
    <div class="ai-overlay" id="aiOverlay" onclick="closeAIPanel()"></div>
    <!-- Panel de IA Flotante -->
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

    <!-- El resto del JavaScript del header se mantiene igual -->
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

    // Chat IA REAL con Gemini
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
            
            // 3. Llamar al Backend
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
                addMessage(data.response, 'ai');
            })
            .catch(error => {
                console.error('Error:', error);
                const loadingElement = document.getElementById(loadingId);
                if(loadingElement) loadingElement.remove();
                addMessage("Lo siento, hubo un error de conexión.", 'ai');
            });
        }
    }
    
    // Función auxiliar actualizada para soportar IDs (para borrar el loading)
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