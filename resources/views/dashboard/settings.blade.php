@extends('layouts.dashboard')

@section('title', 'Configuración - WasiQhari')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Configuración</h1>
            <p>Personaliza tu experiencia en WasiQhari</p>
        </div>
    </div>

    <div class="settings-content">
        <div class="settings-grid">
            <!-- Configuración General -->
            <div class="settings-card">
                <h3><i class="fas fa-user-cog"></i> Configuración de Perfil</h3>
                <form class="settings-form">
                    <div class="form-group">
                        <label for="user_name">Nombre Completo</label>
                        <input type="text" id="user_name" value="{{ Auth::user()->name }}" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="user_email">Correo Electrónico</label>
                        <input type="email" id="user_email" value="{{ Auth::user()->email }}" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="user_phone">Teléfono</label>
                        <input type="tel" id="user_phone" placeholder="+51 XXX XXX XXX" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="user_district">Distrito de Operación</label>
                        <select id="user_district" class="form-control">
                            <option value="cusco" selected>Cusco Centro</option>
                            <option value="wanchaq">Wanchaq</option>
                            <option value="san_sebastian">San Sebastián</option>
                            <option value="santiago">Santiago</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>
            </div>

            <!-- Preferencias de Notificación -->
            <div class="settings-card">
                <h3><i class="fas fa-bell"></i> Notificaciones</h3>
                <div class="settings-list">
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Notificaciones por Email</strong>
                                <p>Recibir alertas y recordatorios por correo</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-mobile-alt"></i>
                            <div>
                                <strong>Notificaciones Push</strong>
                                <p>Alertas en tiempo real en el navegador</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-calendar-check"></i>
                            <div>
                                <strong>Recordatorios de Visitas</strong>
                                <p>Alertas antes de visitas programadas</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                <strong>Alertas de Emergencia</strong>
                                <p>Notificaciones urgentes de casos críticos</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Preferencias de Visualización -->
            <div class="settings-card">
                <h3><i class="fas fa-palette"></i> Apariencia</h3>
                <div class="settings-list">
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-moon"></i>
                            <div>
                                <strong>Modo Oscuro</strong>
                                <p>Interfaz con colores oscuros</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="darkModeToggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-text-height"></i>
                            <div>
                                <strong>Tamaño de Fuente</strong>
                                <p>Ajustar el tamaño del texto</p>
                            </div>
                        </div>
                        <select class="form-control" style="max-width: 150px;">
                            <option value="small">Pequeño</option>
                            <option value="medium" selected>Mediano</option>
                            <option value="large">Grande</option>
                        </select>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-language"></i>
                            <div>
                                <strong>Idioma</strong>
                                <p>Seleccionar idioma de la interfaz</p>
                            </div>
                        </div>
                        <select class="form-control" style="max-width: 150px;">
                            <option value="es" selected>Español</option>
                            <option value="en">English</option>
                            <option value="qu">Quechua</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Configuración de Seguridad -->
            <div class="settings-card">
                <h3><i class="fas fa-shield-alt"></i> Seguridad</h3>
                <div class="security-settings">
                    <div class="security-item">
                        <div class="security-info">
                            <i class="fas fa-key"></i>
                            <div>
                                <strong>Cambiar Contraseña</strong>
                                <p>Actualiza tu contraseña regularmente</p>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="cambiarContrasena()">
                            Cambiar
                        </button>
                    </div>
                    
                    <div class="security-item">
                        <div class="security-info">
                            <i class="fas fa-user-shield"></i>
                            <div>
                                <strong>Autenticación de Dos Factores</strong>
                                <p>Protección adicional para tu cuenta</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="security-item">
                        <div class="security-info">
                            <i class="fas fa-history"></i>
                            <div>
                                <strong>Actividad Reciente</strong>
                                <p>Revisa los inicios de sesión recientes</p>
                            </div>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="verActividad()">
                            Ver
                        </button>
                    </div>
                </div>
            </div>

            <!-- Configuración del Sistema -->
            <div class="settings-card">
                <h3><i class="fas fa-cog"></i> Sistema</h3>
                <div class="system-settings">
                    <div class="system-item">
                        <i class="fas fa-database"></i>
                        <div class="system-info">
                            <strong>Almacenamiento</strong>
                            <p>1.2 GB de 5 GB utilizados</p>
                            <div class="storage-bar">
                                <div class="storage-fill" style="width: 24%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="system-item">
                        <i class="fas fa-sync-alt"></i>
                        <div class="system-info">
                            <strong>Sincronización</strong>
                            <p>Última sincronización: hace 2 horas</p>
                            <button class="btn btn-outline-primary btn-sm" onclick="sincronizarDatos()">
                                Sincronizar Ahora
                            </button>
                        </div>
                    </div>
                    
                    <div class="system-item">
                        <i class="fas fa-trash-alt"></i>
                        <div class="system-info">
                            <strong>Limpiar Cache</strong>
                            <p>Eliminar datos temporales del sistema</p>
                            <button class="btn btn-outline-warning btn-sm" onclick="limpiarCache()">
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de la Cuenta -->
            <div class="settings-card">
                <h3><i class="fas fa-info-circle"></i> Información de la Cuenta</h3>
                <div class="account-info">
                    <div class="info-item">
                        <strong>Plan Actual:</strong>
                        <span class="badge badge-success">Gratuito</span>
                    </div>
                    <div class="info-item">
                        <strong>Miembro desde:</strong>
                        <span>{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Rol:</strong>
                        <span class="text-capitalize">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Último acceso:</strong>
                        <span>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                <div class="account-actions">
                    <button class="btn btn-outline-primary" onclick="exportarDatosUsuario()">
                        <i class="fas fa-download"></i> Exportar Mis Datos
                    </button>
                    <button class="btn btn-outline-danger" onclick="eliminarCuenta()">
                        <i class="fas fa-trash"></i> Eliminar Cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.settings-content {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 25px;
}

.settings-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 1px solid #e0e0e0;
}

.settings-card h3 {
    margin: 0 0 20px 0;
    color: var(--dark-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.settings-card h3 i {
    color: var(--primary-color);
}

.settings-form .form-group {
    margin-bottom: 20px;
}

.settings-list {
    margin-top: 15px;
}

.setting-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.setting-info i {
    font-size: 1.2rem;
    color: var(--primary-color);
    width: 20px;
}

.setting-info strong {
    display: block;
    margin-bottom: 3px;
    color: var(--dark-color);
}

.setting-info p {
    margin: 0;
    font-size: 0.8rem;
    color: var(--text-light);
}

/* Switch */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: var(--primary-color);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Seguridad */
.security-settings {
    margin-top: 15px;
}

.security-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.security-item:last-child {
    border-bottom: none;
}

.security-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.security-info i {
    font-size: 1.2rem;
    color: var(--primary-color);
    width: 20px;
}

/* Sistema */
.system-settings {
    margin-top: 15px;
}

.system-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.system-item:last-child {
    border-bottom: none;
}

.system-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
    width: 30px;
}

.system-info {
    flex: 1;
}

.storage-bar {
    width: 100%;
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 5px;
}

.storage-fill {
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 3px;
}

/* Información de cuenta */
.account-info {
    margin-bottom: 20px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.account-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.badge-success {
    background: #e8f5e8;
    color: #27ae60;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
}

.btn-outline-primary, .btn-outline-secondary, .btn-outline-warning, .btn-outline-danger {
    border: 1px solid;
    background: white;
    transition: var(--transition);
}

.btn-outline-primary {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.btn-outline-warning {
    border-color: #f39c12;
    color: #f39c12;
}

.btn-outline-warning:hover {
    background: #f39c12;
    color: white;
}

.btn-outline-danger {
    border-color: #e74c3c;
    color: #e74c3c;
}

.btn-outline-danger:hover {
    background: #e74c3c;
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .setting-item, .security-item, .system-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .account-actions {
        flex-direction: column;
    }
    
    .settings-card {
        padding: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Modo oscuro
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    // Cargar estado del modo oscuro
    if (localStorage.getItem('darkMode') === 'true') {
        darkModeToggle.checked = true;
        document.body.classList.add('dark-mode');
    }
    
    darkModeToggle.addEventListener('change', function() {
        document.body.classList.toggle('dark-mode', this.checked);
        localStorage.setItem('darkMode', this.checked);
        
        Swal.fire({
            title: 'Modo ' + (this.checked ? 'Oscuro' : 'Claro'),
            text: 'La configuración ha sido guardada.',
            icon: 'success',
            timer: 1500
        });
    });
});

// Funciones de configuración
function cambiarContrasena() {
    Swal.fire({
        title: 'Cambiar Contraseña',
        html: `
            <div class="password-form">
                <div class="form-group">
                    <label>Contraseña Actual</label>
                    <input type="password" class="swal2-input" placeholder="Ingresa tu contraseña actual">
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" class="swal2-input" placeholder="Ingresa nueva contraseña">
                </div>
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" class="swal2-input" placeholder="Confirma nueva contraseña">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Cambiar Contraseña',
        preConfirm: () => {
            return {
                actual: document.querySelectorAll('.swal2-input')[0].value,
                nueva: document.querySelectorAll('.swal2-input')[1].value,
                confirmar: document.querySelectorAll('.swal2-input')[2].value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                '¡Contraseña Cambiada!',
                'Tu contraseña ha sido actualizada exitosamente.',
                'success'
            );
        }
    });
}

function verActividad() {
    Swal.fire({
        title: 'Actividad Reciente',
        html: `
            <div class="activity-list">
                <div class="activity-item success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Inicio de Sesión</strong>
                        <p>Desde Chrome - Windows</p>
                        <small>Hace 2 horas</small>
                    </div>
                </div>
                <div class="activity-item">
                    <i class="fas fa-mobile-alt"></i>
                    <div>
                        <strong>Acceso Móvil</strong>
                        <p>Desde Android App</p>
                        <small>Ayer, 15:30</small>
                    </div>
                </div>
                <div class="activity-item warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Intento Fallido</strong>
                        <p>Contraseña incorrecta</p>
                        <small>Hace 3 días</small>
                    </div>
                </div>
            </div>
        `,
        width: 500,
        confirmButtonText: 'Cerrar'
    });
}

function sincronizarDatos() {
    Swal.fire({
        title: 'Sincronizando Datos',
        html: `
            <div class="sync-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
                <p>Actualizando información local...</p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    // Simular progreso de sincronización
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        document.querySelector('.progress-fill').style.width = progress + '%';
        
        if (progress >= 100) {
            clearInterval(interval);
            Swal.fire({
                title: '¡Sincronización Completa!',
                text: 'Todos los datos han sido actualizados.',
                icon: 'success',
                timer: 2000
            });
        }
    }, 200);
}

function limpiarCache() {
    Swal.fire({
        title: '¿Limpiar Cache?',
        text: 'Se eliminarán los datos temporales del sistema',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Limpiando...',
                text: 'Eliminando datos temporales',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                Swal.fire(
                    '¡Cache Limpiado!',
                    'Los datos temporales han sido eliminados.',
                    'success'
                );
            });
        }
    });
}

function exportarDatosUsuario() {
    Swal.fire({
        title: 'Exportando Datos',
        text: 'Preparando archivo con tu información...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire(
            '¡Exportación Completa!',
            'Tu información ha sido exportada en formato ZIP.',
            'success'
        );
    });
}

function eliminarCuenta() {
    Swal.fire({
        title: '¿Eliminar Cuenta?',
        text: 'Esta acción no se puede deshacer. Se perderán todos tus datos.',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Sí, eliminar cuenta',
        cancelButtonText: 'Cancelar',
        input: 'text',
        inputPlaceholder: 'Escribe "ELIMINAR" para confirmar',
        inputValidator: (value) => {
            if (value !== 'ELIMINAR') {
                return 'Debes escribir ELIMINAR para confirmar';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Cuenta Eliminada',
                'Tu cuenta ha sido eliminada permanentemente.',
                'success'
            );
        }
    });
}

// Guardar configuración del formulario
document.querySelector('.settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: '¡Configuración Guardada!',
        text: 'Tus cambios han sido guardados exitosamente.',
        icon: 'success',
        timer: 1500
    });
});

// Estilos adicionales para los modales
const additionalStyles = `
.password-form .form-group {
    margin-bottom: 15px;
    text-align: left;
}

.password-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.activity-list {
    text-align: left;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    margin: 10px 0;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.activity-item.success {
    border-left-color: #27ae60;
}

.activity-item.warning {
    border-left-color: #f39c12;
}

.activity-item i {
    font-size: 1.5rem;
    color: #3498db;
}

.activity-item.success i {
    color: #27ae60;
}

.activity-item.warning i {
    color: #f39c12;
}

.activity-item strong {
    display: block;
    margin-bottom: 2px;
}

.activity-item p {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text-light);
}

.activity-item small {
    color: var(--text-light);
    font-size: 0.8rem;
}

.sync-progress .progress-bar {
    width: 100%;
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    overflow: hidden;
    margin: 15px 0;
}

.sync-progress .progress-fill {
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    transition: width 0.3s ease;
    border-radius: 5px;
}

body.dark-mode {
    background: #1a1a1a;
    color: #e0e0e0;
}

body.dark-mode .settings-card {
    background: #2d2d2d;
    border-color: #444;
}

body.dark-mode .setting-item,
body.dark-mode .security-item,
body.dark-mode .system-item,
body.dark-mode .info-item {
    border-bottom-color: #444;
}

body.dark-mode .setting-info p,
body.dark-mode .system-info p {
    color: #b0b0b0;
}

body.dark-mode .form-control {
    background: #3d3d3d;
    border-color: #555;
    color: #e0e0e0;
}

body.dark-mode .storage-bar {
    background: #444;
}

body.dark-mode .activity-item {
    background: #3d3d3d;
}
`;

// Inject additional styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
</script>
@endpush