<section class="auth-section">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-heart"></i>
                    <span>WasiQhari</span>
                </div>
                <h2>Crear Cuenta</h2>
                <p>Únete a nuestra comunidad solidaria</p>
            </div>
            
            <form class="auth-form" action="index.php?c=user&a=register" method="POST">
                <div class="form-group">
                    <label for="name">Nombre Completo *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" required placeholder="Tu nombre completo">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required placeholder="tu@email.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required placeholder="Mínimo 8 caracteres">
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar"></div>
                        <span class="strength-text">Seguridad de la contraseña</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repite tu contraseña">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="role">¿Cómo quieres participar? *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user-tag"></i>
                        <select id="role" name="role" required>
                            <option value="">Selecciona tu rol</option>
                            <option value="volunteer">Voluntario</option>
                            <option value="family">Familiar de adulto mayor</option>
                            <option value="organization">Organización/ONG</option>
                            <option value="elderly">Adulto mayor</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="organizationField" style="display: none;">
                    <label for="organization_name">Nombre de la Organización</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building"></i>
                        <input type="text" id="organization_name" name="organization_name" placeholder="Nombre de tu organización">
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        Acepto los <a href="#" class="link">términos y condiciones</a> y la <a href="#" class="link">política de privacidad</a>
                    </label>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="newsletter">
                        <span class="checkmark"></span>
                        Quiero recibir noticias y actualizaciones de WasiQhari
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-full btn-auth">
                    <i class="fas fa-user-plus"></i> Crear Cuenta
                </button>
                
                <div class="auth-divider">
                    <span>o regístrate con</span>
                </div>
                
                <div class="social-auth">
                    <button type="button" class="btn btn-social btn-google">
                        <i class="fab fa-google"></i> Google
                    </button>
                    <button type="button" class="btn btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </button>
                </div>
            </form>
            
            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="index.php?c=user&a=login">Inicia sesión aquí</a></p>
            </div>
        </div>
        
        <div class="auth-welcome">
            <div class="welcome-content">
                <h2>Únete a Nuestra Misión</h2>
                <p>Forma parte de esta increíble comunidad que está transformando la vida de adultos mayores en situación de vulnerabilidad.</p>
                
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4>Impacto Real</h4>
                        <p>Marca la diferencia en la vida de personas que realmente necesitan tu ayuda</p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Comunidad Solidaria</h4>
                        <p>Conecta con personas increíbles que comparten tu pasión por ayudar</p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h4>Reconocimiento</h4>
                        <p>Recibe certificados y reconocimientos por tu labor voluntaria</p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Crecimiento Personal</h4>
                        <p>Desarrolla nuevas habilidades y experiencias enriquecedoras</p>
                    </div>
                </div>
                
                <div class="stats-preview">
                    <div class="stat-preview">
                        <h3>500+</h3>
                        <p>Adultos Mayores Ayudados</p>
                    </div>
                    <div class="stat-preview">
                        <h3>150+</h3>
                        <p>Voluntarios Activos</p>
                    </div>
                    <div class="stat-preview">
                        <h3>98%</h3>
                        <p>Satisfacción</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Mostrar/ocultar campo de organización
document.getElementById('role').addEventListener('change', function() {
    const organizationField = document.getElementById('organizationField');
    if (this.value === 'organization') {
        organizationField.style.display = 'block';
    } else {
        organizationField.style.display = 'none';
    }
});

// Validación de contraseña
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    
    let strength = 0;
    let text = 'Débil';
    let color = '#e74c3c';
    
    if (password.length >= 8) strength += 25;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
    if (password.match(/\d/)) strength += 25;
    if (password.match(/[^a-zA-Z\d]/)) strength += 25;
    
    if (strength >= 75) {
        text = 'Fuerte';
        color = '#27ae60';
    } else if (strength >= 50) {
        text = 'Media';
        color = '#f39c12';
    } else if (strength >= 25) {
        text = 'Débil';
        color = '#e74c3c';
    } else {
        text = 'Muy débil';
        color = '#c0392b';
    }
    
    strengthBar.style.width = strength + '%';
    strengthBar.style.background = color;
    strengthText.textContent = text;
    strengthText.style.color = color;
});

// Validación de confirmación de contraseña
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.style.borderColor = '#e74c3c';
    } else {
        this.style.borderColor = '#27ae60';
    }
});
</script>