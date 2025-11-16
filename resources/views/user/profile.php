<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Mi Perfil</h1>
            <p>Gestiona tu información personal y preferencias</p>
        </div>
    </div>

    <div class="profile-content">
        <div class="profile-grid">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo $_SESSION['user_name']; ?></h2>
                        <p><?php echo $_SESSION['user_email']; ?></p>
                        <span class="user-role"><?php echo ucfirst($_SESSION['user_role']); ?></span>
                    </div>
                </div>
                
                <div class="profile-stats">
                    <div class="stat">
                        <strong>25</strong>
                        <span>Actividades</span>
                    </div>
                    <div class="stat">
                        <strong>12</strong>
                        <span>Ayudados</span>
                    </div>
                    <div class="stat">
                        <strong>8</strong>
                        <span>Voluntarios</span>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <h3>Información Personal</h3>
                <form class="profile-form">
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" value="<?php echo $_SESSION['user_name']; ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" value="<?php echo $_SESSION['user_email']; ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" placeholder="+51 XXX XXX XXX" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Zona de Operación</label>
                        <select class="form-control">
                            <option>Cusco Centro</option>
                            <option>San Sebastián</option>
                            <option>San Jerónimo</option>
                            <option>Wanchaq</option>
                            <option>Santiago</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>
            </div>

            <div class="profile-card">
                <h3>Configuración de Cuenta</h3>
                
                <div class="settings-list">
                    <div class="setting-item">
                        <div class="setting-info">
                            <i class="fas fa-bell"></i>
                            <div>
                                <strong>Notificaciones</strong>
                                <p>Recibir alertas y recordatorios</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
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
                            <i class="fas fa-robot"></i>
                            <div>
                                <strong>Asistente IA</strong>
                                <p>Habilitar sugerencias inteligentes</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <h3>Mi Impacto</h3>
                <div class="impact-stats">
                    <div class="impact-item success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>45</strong>
                            <span>Visitas Completadas</span>
                        </div>
                    </div>
                    
                    <div class="impact-item warning">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>8</strong>
                            <span>Pendientes</span>
                        </div>
                    </div>
                    
                    <div class="impact-item primary">
                        <i class="fas fa-users"></i>
                        <div>
                            <strong>15</strong>
                            <span>Adultos Ayudados</span>
                        </div>
                    </div>
                    
                    <div class="impact-item info">
                        <i class="fas fa-star"></i>
                        <div>
                            <strong>4.8</strong>
                            <span>Calificación</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-content {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.profile-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 1px solid #e0e0e0;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.profile-info h2 {
    margin: 0 0 5px 0;
    color: var(--dark-color);
    font-size: 1.5rem;
}

.profile-info p {
    margin: 0 0 8px 0;
    color: var(--text-color);
}

.user-role {
    background: var(--primary-color);
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.stat {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: var(--transition);
}

.stat:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.stat strong {
    display: block;
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.stat span {
    font-size: 0.8rem;
    opacity: 0.8;
}

.profile-form {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--dark-color);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.settings-list {
    margin-top: 20px;
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

.impact-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: 15px;
}

.impact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    border-radius: 10px;
    background: #f8f9fa;
    transition: var(--transition);
}

.impact-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.impact-item.success {
    border-left: 4px solid #28a745;
}

.impact-item.warning {
    border-left: 4px solid #ffc107;
}

.impact-item.primary {
    border-left: 4px solid var(--primary-color);
}

.impact-item.info {
    border-left: 4px solid #17a2b8;
}

.impact-item i {
    font-size: 1.5rem;
}

.impact-item.success i { color: #28a745; }
.impact-item.warning i { color: #ffc107; }
.impact-item.primary i { color: var(--primary-color); }
.impact-item.info i { color: #17a2b8; }

.impact-item strong {
    display: block;
    font-size: 1.3rem;
    margin-bottom: 2px;
}

.impact-item span {
    font-size: 0.8rem;
    color: var(--text-light);
}

/* Responsive */
@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-stats {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .impact-stats {
        grid-template-columns: 1fr;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .setting-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<script>
// Modo oscuro en perfil
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    // Cargar estado del modo oscuro
    if (localStorage.getItem('darkMode') === 'true') {
        darkModeToggle.checked = true;
    }
    
    darkModeToggle.addEventListener('change', function() {
        document.body.classList.toggle('dark-mode', this.checked);
        localStorage.setItem('darkMode', this.checked);
        
        // Actualizar icono en el header si existe
        const headerIcon = document.querySelector('.btn-mode i');
        if (headerIcon) {
            if (this.checked) {
                headerIcon.classList.remove('fa-moon');
                headerIcon.classList.add('fa-sun');
            } else {
                headerIcon.classList.remove('fa-sun');
                headerIcon.classList.add('fa-moon');
            }
        }
    });
});

// Simular guardado de formulario
document.querySelector('.profile-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: '¡Éxito!',
        text: 'Tu perfil ha sido actualizado correctamente',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    });
});
</script>