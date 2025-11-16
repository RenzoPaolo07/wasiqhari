<!-- wasiqhari/views/dashboard/notificaciones.php -->
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Comunicación y Alertas</h1>
            <p>Sistema de notificaciones y chat interno</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="abrirModalNuevaNotificacion()">
                <i class="fas fa-bell"></i> Nueva Notificación
            </button>
            <button class="btn btn-secondary" onclick="iniciarLlamadaGrupo()">
                <i class="fas fa-phone"></i> Llamada Grupal
            </button>
        </div>
    </div>

    <div class="comunicacion-grid">
        <!-- Panel de Notificaciones -->
        <div class="comunicacion-panel">
            <div class="panel-header">
                <h3>🔔 Notificaciones Recientes</h3>
                <button class="btn-icon" onclick="marcarTodasLeidas()">
                    <i class="fas fa-check-double"></i>
                </button>
            </div>
            <div class="notificaciones-list" id="listaNotificaciones">
                <!-- Notificaciones se cargarán aquí -->
            </div>
        </div>

        <!-- Chat Interno -->
        <div class="comunicacion-panel">
            <div class="panel-header">
                <h3>💬 Chat de Equipo</h3>
                <div class="chat-actions">
                    <button class="btn-icon" onclick="alternarModoChat()">
                        <i class="fas fa-moon"></i>
                    </button>
                    <button class="btn-icon" onclick="limpiarChat()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="chat-container">
                <div class="chat-messages" id="chatMessages">
                    <!-- Mensajes del chat -->
                </div>
                <div class="chat-input-container">
                    <input type="text" id="chatInput" placeholder="Escribe un mensaje..." 
                           onkeypress="manejarEnterChat(event)">
                    <button class="btn btn-primary" onclick="enviarMensaje()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas en Tiempo Real -->
    <div class="alertas-tiempo-real">
        <h3>🚨 Alertas Activas</h3>
        <div class="alertas-grid" id="alertasGrid">
            <!-- Alertas se cargarán dinámicamente -->
        </div>
    </div>
</div>

<!-- Modal Nueva Notificación -->
<div id="notificacionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nueva Notificación</h3>
            <span class="close">&times;</span>
        </div>
        <form id="notificacionForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="notif_titulo">Título *</label>
                    <input type="text" id="notif_titulo" required placeholder="Asunto de la notificación">
                </div>
                
                <div class="form-group">
                    <label for="notif_mensaje">Mensaje *</label>
                    <textarea id="notif_mensaje" required rows="4" placeholder="Contenido del mensaje..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="notif_prioridad">Prioridad</label>
                    <select id="notif_prioridad">
                        <option value="baja">Baja</option>
                        <option value="media" selected>Media</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notif_destinatarios">Destinatarios</label>
                    <select id="notif_destinatarios" multiple>
                        <option value="todos" selected>Todos los voluntarios</option>
                        <option value="administradores">Solo administradores</option>
                        <option value="cusco">Voluntarios Cusco</option>
                        <option value="wanchaq">Voluntarios Wanchaq</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="notif_emergencia">
                        <span class="checkmark"></span>
                        Notificación de emergencia
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalNotificacion()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Enviar Notificación</button>
            </div>
        </form>
    </div>
</div>

<script>
// Sistema de Notificaciones
class SistemaNotificaciones {
    constructor() {
        this.notificaciones = JSON.parse(localStorage.getItem('notificaciones_wasiqhari')) || [];
        this.mensajesChat = JSON.parse(localStorage.getItem('chat_wasiqhari')) || [];
        this.alertasActivas = [];
        this.inicializar();
    }

    inicializar() {
        this.cargarNotificaciones();
        this.cargarChat();
        this.iniciarSimulacionAlertas();
        this.iniciarWebSocket();
    }

    cargarNotificaciones() {
        const lista = document.getElementById('listaNotificaciones');
        if (this.notificaciones.length === 0) {
            lista.innerHTML = `
                <div class="no-notificaciones">
                    <i class="fas fa-bell-slash"></i>
                    <p>No hay notificaciones</p>
                </div>
            `;
            return;
        }

        lista.innerHTML = this.notificaciones.map(notif => `
            <div class="notificacion-item ${notif.leida ? '' : 'no-leida'} prioridad-${notif.prioridad}">
                <div class="notificacion-icon">
                    ${this.obtenerIconoPrioridad(notif.prioridad)}
                </div>
                <div class="notificacion-content">
                    <h4>${notif.titulo}</h4>
                    <p>${notif.mensaje}</p>
                    <small>${this.formatearFecha(notif.fecha)}</small>
                </div>
                <div class="notificacion-actions">
                    ${!notif.leida ? `<button class="btn-icon" onclick="sistemaNotif.marcarLeida(${notif.id})">
                        <i class="fas fa-check"></i>
                    </button>` : ''}
                    <button class="btn-icon" onclick="sistemaNotif.eliminarNotificacion(${notif.id})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    cargarChat() {
        const chat = document.getElementById('chatMessages');
        chat.innerHTML = this.mensajesChat.map(msg => `
            <div class="mensaje ${msg.usuario === 'Tú' ? 'propio' : ''}">
                <div class="mensaje-header">
                    <strong>${msg.usuario}</strong>
                    <span class="hora-mensaje">${msg.hora}</span>
                </div>
                <div class="mensaje-content">${msg.contenido}</div>
            </div>
        `).join('');
        chat.scrollTop = chat.scrollHeight;
    }

    agregarNotificacion(titulo, mensaje, prioridad = 'media') {
        const nuevaNotif = {
            id: Date.now(),
            titulo,
            mensaje,
            prioridad,
            fecha: new Date(),
            leida: false
        };
        
        this.notificaciones.unshift(nuevaNotif);
        this.guardarDatos();
        this.cargarNotificaciones();
        this.mostrarNotificacionPush(nuevaNotif);
    }

    agregarMensajeChat(usuario, contenido) {
        const nuevoMsg = {
            usuario,
            contenido,
            hora: new Date().toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' })
        };
        
        this.mensajesChat.push(nuevoMsg);
        this.guardarDatos();
        this.cargarChat();
    }

    marcarLeida(id) {
        const notif = this.notificaciones.find(n => n.id === id);
        if (notif) {
            notif.leida = true;
            this.guardarDatos();
            this.cargarNotificaciones();
        }
    }

    eliminarNotificacion(id) {
        this.notificaciones = this.notificaciones.filter(n => n.id !== id);
        this.guardarDatos();
        this.cargarNotificaciones();
    }

    mostrarNotificacionPush(notificacion) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(notificacion.titulo, {
                body: notificacion.mensaje,
                icon: '/assets/images/logo.png',
                tag: 'wasiqhari'
            });
        }
    }

    iniciarSimulacionAlertas() {
        // Simular alertas en tiempo real
        setInterval(() => {
            if (Math.random() > 0.7) { // 30% de probabilidad
                this.generarAlertaAleatoria();
            }
        }, 30000); // Cada 30 segundos
    }

    generarAlertaAleatoria() {
        const alertas = [
            {
                tipo: 'salud',
                titulo: 'Alerta de Salud',
                mensaje: 'Adulto mayor requiere atención médica urgente',
                prioridad: 'alta'
            },
            {
                tipo: 'visita',
                titulo: 'Visita Pendiente',
                mensaje: 'Recordatorio: visita programada para hoy',
                prioridad: 'media'
            },
            {
                tipo: 'voluntario',
                titulo: 'Nuevo Voluntario',
                mensaje: 'Se ha registrado un nuevo voluntario',
                prioridad: 'baja'
            }
        ];

        const alerta = alertas[Math.floor(Math.random() * alertas.length)];
        this.mostrarAlertaTiempoReal(alerta);
    }

    mostrarAlertaTiempoReal(alerta) {
        const alertaElement = document.createElement('div');
        alertaElement.className = `alerta-item alerta-${alerta.prioridad}`;
        alertaElement.innerHTML = `
            <div class="alerta-icon">
                <i class="fas fa-${this.obtenerIconoAlerta(alerta.tipo)}"></i>
            </div>
            <div class="alerta-content">
                <h4>${alerta.titulo}</h4>
                <p>${alerta.mensaje}</p>
            </div>
            <button class="btn-icon" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.getElementById('alertasGrid').appendChild(alertaElement);
        
        // Auto-eliminar después de 10 segundos
        setTimeout(() => {
            if (alertaElement.parentElement) {
                alertaElement.remove();
            }
        }, 10000);
    }

    obtenerIconoPrioridad(prioridad) {
        const iconos = {
            baja: '🔵',
            media: '🟡',
            alta: '🟠',
            urgente: '🔴'
        };
        return iconos[prioridad] || '🔵';
    }

    obtenerIconoAlerta(tipo) {
        const iconos = {
            salud: 'heartbeat',
            visita: 'home',
            voluntario: 'user-plus'
        };
        return iconos[tipo] || 'bell';
    }

    formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    guardarDatos() {
        localStorage.setItem('notificaciones_wasiqhari', JSON.stringify(this.notificaciones));
        localStorage.setItem('chat_wasiqhari', JSON.stringify(this.mensajesChat));
    }

    iniciarWebSocket() {
        // Simular conexión WebSocket para notificaciones en tiempo real
        console.log('Sistema de notificaciones WebSocket iniciado');
    }
}

// Inicializar sistema
const sistemaNotif = new SistemaNotificaciones();

// Funciones globales
function abrirModalNuevaNotificacion() {
    document.getElementById('notificacionModal').style.display = 'block';
}

function cerrarModalNotificacion() {
    document.getElementById('notificacionModal').style.display = 'none';
}

function marcarTodasLeidas() {
    sistemaNotif.notificaciones.forEach(notif => notif.leida = true);
    sistemaNotif.guardarDatos();
    sistemaNotif.cargarNotificaciones();
    Swal.fire('¡Listo!', 'Todas las notificaciones marcadas como leídas', 'success');
}

function alternarModoChat() {
    document.body.classList.toggle('modo-oscuro');
    localStorage.setItem('modoOscuro', document.body.classList.contains('modo-oscuro'));
}

function limpiarChat() {
    Swal.fire({
        title: '¿Limpiar chat?',
        text: 'Se eliminarán todos los mensajes',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            sistemaNotif.mensajesChat = [];
            sistemaNotif.guardarDatos();
            sistemaNotif.cargarChat();
        }
    });
}

function manejarEnterChat(event) {
    if (event.key === 'Enter') {
        enviarMensaje();
    }
}

function enviarMensaje() {
    const input = document.getElementById('chatInput');
    const mensaje = input.value.trim();
    
    if (mensaje) {
        sistemaNotif.agregarMensajeChat('Tú', mensaje);
        input.value = '';
        
        // Simular respuesta automática
        setTimeout(() => {
            const respuestas = [
                '¡Gracias por la información!',
                'Entendido, procederemos con la acción correspondiente',
                'Mensaje recibido, ¿necesitas algo más?',
                'Perfecto, lo tendremos en cuenta'
            ];
            const respuesta = respuestas[Math.floor(Math.random() * respuestas.length)];
            sistemaNotif.agregarMensajeChat('Sistema WasiQhari', respuesta);
        }, 1000 + Math.random() * 2000);
    }
}

function iniciarLlamadaGrupo() {
    Swal.fire({
        title: 'Iniciar Llamada Grupal',
        html: `
            <div class="llamada-form">
                <div class="form-group">
                    <label>Asunto de la llamada</label>
                    <input type="text" class="swal2-input" placeholder="Ej: Reunión de coordinación">
                </div>
                <div class="form-group">
                    <label>Participantes</label>
                    <select class="swal2-input" multiple>
                        <option value="todos" selected>Todos los voluntarios activos</option>
                        <option value="administradores">Equipo administrativo</option>
                        <option value="cusco">Voluntarios Cusco</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Duración estimada</label>
                    <select class="swal2-input">
                        <option value="15">15 minutos</option>
                        <option value="30" selected>30 minutos</option>
                        <option value="45">45 minutos</option>
                        <option value="60">1 hora</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Iniciar Llamada',
        preConfirm: () => {
            return {
                asunto: document.querySelectorAll('.swal2-input')[0].value,
                participantes: Array.from(document.querySelectorAll('.swal2-input')[1].selectedOptions).map(opt => opt.value),
                duracion: document.querySelectorAll('.swal2-input')[2].value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                '¡Llamada Iniciada!',
                'Se ha enviado la invitación a todos los participantes',
                'success'
            );
            
            // Simular notificación de llamada
            sistemaNotif.agregarNotificacion(
                'Llamada Grupal Iniciada',
                `"${result.value.asunto}" - Duración: ${result.value.duracion} min`,
                'alta'
            );
        }
    });
}

// Solicitar permisos de notificación
if ('Notification' in window) {
    Notification.requestPermission();
}

// Cerrar modales
document.querySelectorAll('.close').forEach(close => {
    close.onclick = function() {
        this.closest('.modal').style.display = 'none';
    };
});

window.onclick = (event) => {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
};

// Envío del formulario de notificación
document.getElementById('notificacionForm').onsubmit = (e) => {
    e.preventDefault();
    
    const titulo = document.getElementById('notif_titulo').value;
    const mensaje = document.getElementById('notif_mensaje').value;
    const prioridad = document.getElementById('notif_prioridad').value;
    
    sistemaNotif.agregarNotificacion(titulo, mensaje, prioridad);
    
    Swal.fire('¡Enviado!', 'La notificación ha sido enviada', 'success');
    cerrarModalNotificacion();
    e.target.reset();
};

// Cargar modo oscuro si estaba activo
if (localStorage.getItem('modoOscuro') === 'true') {
    document.body.classList.add('modo-oscuro');
}

// Agregar algunos mensajes de ejemplo al chat
if (sistemaNotif.mensajesChat.length === 0) {
    sistemaNotif.agregarMensajeChat('Sistema WasiQhari', '¡Bienvenido al chat de equipo! 👋');
    sistemaNotif.agregarMensajeChat('Sistema WasiQhari', 'Puedes coordinar visitas y compartir información importante aquí.');
}
</script>

<style>
.comunicacion-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}

.comunicacion-panel {
    background: white;
    border-radius: 15px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.panel-header {
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
}

.panel-header h3 {
    margin: 0;
    color: var(--dark-color);
}

.notificaciones-list {
    max-height: 400px;
    overflow-y: auto;
}

.no-notificaciones {
    padding: 40px 20px;
    text-align: center;
    color: var(--text-light);
}

.no-notificaciones i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}

.notificacion-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px 20px;
    border-bottom: 1px solid #f5f5f5;
    transition: var(--transition);
}

.notificacion-item:hover {
    background: #f8f9fa;
}

.notificacion-item.no-leida {
    background: #f0f7ff;
    border-left: 3px solid var(--primary-color);
}

.notificacion-item.prioridad-urgente {
    background: #fff5f5;
    border-left: 3px solid #e74c3c;
}

.notificacion-item.prioridad-alta {
    background: #fffbf0;
    border-left: 3px solid #f39c12;
}

.notificacion-icon {
    font-size: 1.2rem;
    margin-top: 2px;
}

.notificacion-content {
    flex: 1;
}

.notificacion-content h4 {
    margin: 0 0 5px 0;
    color: var(--dark-color);
}

.notificacion-content p {
    margin: 0 0 5px 0;
    color: var(--text-color);
    font-size: 0.9rem;
}

.notificacion-content small {
    color: var(--text-light);
    font-size: 0.8rem;
}

.notificacion-actions {
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: var(--transition);
}

.notificacion-item:hover .notificacion-actions {
    opacity: 1;
}

.chat-container {
    display: flex;
    flex-direction: column;
    height: 400px;
}

.chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.mensaje {
    max-width: 80%;
    padding: 12px 16px;
    border-radius: 15px;
    background: #f1f3f5;
    align-self: flex-start;
}

.mensaje.propio {
    background: var(--primary-color);
    color: white;
    align-self: flex-end;
}

.mensaje-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.mensaje-header strong {
    font-size: 0.8rem;
}

.hora-mensaje {
    font-size: 0.7rem;
    opacity: 0.7;
}

.mensaje.propio .mensaje-header strong {
    color: rgba(255,255,255,0.9);
}

.mensaje-content {
    font-size: 0.9rem;
    line-height: 1.4;
}

.chat-input-container {
    padding: 15px 20px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 10px;
    background: #f8f9fa;
}

.chat-input-container input {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 25px;
    outline: none;
    transition: var(--transition);
}

.chat-input-container input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.alertas-tiempo-real {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: var(--shadow);
}

.alertas-tiempo-real h3 {
    margin: 0 0 20px 0;
    color: var(--dark-color);
}

.alertas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.alerta-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alerta-alta {
    background: #fff5f5;
    border-left-color: #e74c3c;
}

.alerta-media {
    background: #fffbf0;
    border-left-color: #f39c12;
}

.alerta-baja {
    background: #f0f7ff;
    border-left-color: #3498db;
}

.alerta-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.alerta-alta .alerta-icon {
    color: #e74c3c;
}

.alerta-media .alerta-icon {
    color: #f39c12;
}

.alerta-baja .alerta-icon {
    color: #3498db;
}

.alerta-content {
    flex: 1;
}

.alerta-content h4 {
    margin: 0 0 5px 0;
    color: var(--dark-color);
}

.alerta-content p {
    margin: 0;
    color: var(--text-color);
    font-size: 0.9rem;
}

.chat-actions {
    display: flex;
    gap: 5px;
}

/* Modo Oscuro */
body.modo-oscuro {
    background: #1a1a1a;
    color: #e0e0e0;
}

body.modo-oscuro .comunicacion-panel,
body.modo-oscuro .alertas-tiempo-real {
    background: #2d2d2d;
    color: #e0e0e0;
}

body.modo-oscuro .panel-header {
    background: #3d3d3d;
    border-bottom-color: #404040;
}

body.modo-oscuro .notificacion-item {
    border-bottom-color: #404040;
}

body.modo-oscuro .notificacion-item:hover {
    background: #3d3d3d;
}

body.modo-oscuro .chat-input-container {
    background: #3d3d3d;
    border-top-color: #404040;
}

body.modo-oscuro .chat-input-container input {
    background: #4d4d4d;
    border-color: #555;
    color: #e0e0e0;
}

body.modo-oscuro .mensaje:not(.propio) {
    background: #3d3d3d;
    color: #e0e0e0;
}

.llamada-form .form-group {
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .comunicacion-grid {
        grid-template-columns: 1fr;
    }
    
    .mensaje {
        max-width: 90%;
    }
    
    .alertas-grid {
        grid-template-columns: 1fr;
    }
}
</style>