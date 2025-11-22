@extends('layouts.dashboard')

@section('title', 'Mi Perfil - WasiQhari')

@section('content')
<div class="dashboard-container">
    
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#e74c3c'
                });
            });
        </script>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Por favor corrige los siguientes errores:</strong>
            <ul>
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-layout">
        
        <div class="profile-sidebar">
            <div class="user-card">
                <div class="card-body text-center">
                    <div class="avatar-container">
                        <form id="avatarForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                            
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="user-avatar-img">
                            @else
                                <div class="user-avatar-placeholder">
                                    <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <label for="avatarUpload" class="avatar-edit-btn" title="Cambiar foto">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="avatarUpload" name="avatar" style="display: none;" onchange="document.getElementById('avatarForm').submit()">
                        </form>
                    </div>

                    <h2 class="user-name">{{ Auth::user()->name }}</h2>
                    <p class="user-email">{{ Auth::user()->email }}</p>
                    <span class="badge badge-role">{{ ucfirst(Auth::user()->role) }}</span>
                    
                    <div class="user-stats">
                        <div class="stat-box">
                            <strong>{{ Auth::user()->created_at->format('d/m/Y') }}</strong>
                            <span>Miembro desde</span>
                        </div>
                    </div>
                    @if(Auth::user()->role == 'voluntario')
                        <div class="gamification-box" style="margin-top: 20px; text-align: left;">
                            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:5px;">
                                <strong>Nivel: {{ $nivel }}</strong>
                                <span>{{ $puntos }} / {{ $proxNivel }} XP</span>
                            </div>
                            <div style="background:#eee; border-radius:10px; height:10px; width:100%; overflow:hidden;">
                                <div style="background: linear-gradient(90deg, #e74c3c, #f39c12); height:100%; width: {{ ($puntos / $proxNivel) * 100 }}%;"></div>
                            </div>
                            <p style="font-size:0.75rem; color:#888; margin-top:5px; text-align:center;">
                                ¡Realiza más visitas para subir de nivel!
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="content-card mt-4">
                <div class="card-body p-0">
                    <ul class="profile-menu">
                        <li class="active" onclick="switchTab('personal', this)">
                            <a href="javascript:void(0)">
                                <i class="fas fa-user-circle"></i> Información Personal
                            </a>
                        </li>
                        <li onclick="switchTab('security', this)">
                            <a href="javascript:void(0)">
                                <i class="fas fa-shield-alt"></i> Seguridad
                            </a>
                        </li>
                        <li onclick="switchTab('notifications', this)">
                            <a href="javascript:void(0)">
                                <i class="fas fa-bell"></i> Notificaciones
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="profile-main">
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div id="tab-personal" class="tab-content content-card active">
                    <div class="card-header">
                        <h3><i class="fas fa-edit"></i> Información Personal</h3>
                    </div>
                    <div class="card-body">
                        <h4 class="section-title">Datos Básicos</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nombre Completo</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control" required>
                            </div>
                        </div>

                        @if(Auth::user()->role == 'voluntario')
                            <hr class="separator">
                            <h4 class="section-title">Perfil de Voluntario</h4>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="tel" name="telefono" value="{{ $voluntario->telefono ?? '' }}" placeholder="+51..." class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Distrito Base</label>
                                    <select name="distrito" class="form-control">
                                        <option value="">Selecciona...</option>
                                        @foreach(['Cusco', 'Wanchaq', 'San Sebastián', 'Santiago', 'San Jerónimo', 'Poroy'] as $dist)
                                            <option value="{{ $dist }}" {{ ($voluntario->distrito ?? '') == $dist ? 'selected' : '' }}>{{ $dist }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" name="direccion" value="{{ $voluntario->direccion ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Disponibilidad</label>
                                    <select name="disponibilidad" class="form-control">
                                        @foreach(['Mañanas', 'Tardes', 'Noches', 'Fines de semana', 'Flexible'] as $disp)
                                            <option value="{{ $disp }}" {{ ($voluntario->disponibilidad ?? '') == $disp ? 'selected' : '' }}>{{ $disp }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group full-width">
                                    <label>Zonas de Cobertura</label>
                                    <input type="text" name="zona_cobertura" value="{{ $voluntario->zona_cobertura ?? '' }}" placeholder="Ej: Centro Histórico, Magisterio..." class="form-control">
                                </div>
                                <div class="form-group full-width">
                                    <label>Habilidades</label>
                                    <textarea name="habilidades" rows="3" class="form-control">{{ $voluntario->habilidades ?? '' }}</textarea>
                                </div>
                            </div>
                        @endif

                        <div class="form-actions-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>

                <div id="tab-security" class="tab-content content-card" style="display: none;">
                    <div class="card-header">
                        <h3><i class="fas fa-lock"></i> Seguridad y Contraseña</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert" style="background: #fff8e9; border-color: #ffeeba; color: #856404;">
                            <i class="fas fa-info-circle"></i> Deja estos campos en blanco si no deseas cambiar tu contraseña.
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label>Contraseña Actual</label>
                                <input type="password" name="current_password" class="form-control" placeholder="********">
                            </div>
                            <div class="form-group">
                                <label>Nueva Contraseña</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Mínimo 6 caracteres">
                            </div>
                            <div class="form-group">
                                <label>Confirmar Nueva Contraseña</label>
                                <input type="password" name="new_password_confirmation" class="form-control" placeholder="Repite la nueva contraseña">
                            </div>
                        </div>

                        <div class="form-actions-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </div>
                </div>

            </form> <div id="tab-notifications" class="tab-content content-card" style="display: none;">
                <div class="card-header">
                    <h3><i class="fas fa-bell"></i> Preferencias de Notificaciones</h3>
                </div>
                <div class="card-body">
                    <div class="settings-list">
                        <div class="setting-item">
                            <div class="setting-info">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>Alertas por Email</strong>
                                    <p>Recibir correos sobre nuevas visitas asignadas.</p>
                                </div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        
                        <div class="setting-item">
                            <div class="setting-info">
                                <i class="fas fa-desktop"></i>
                                <div>
                                    <strong>Notificaciones de Escritorio</strong>
                                    <p>Mostrar pop-ups mientras usas la plataforma.</p>
                                </div>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <div class="setting-info">
                                <i class="fas fa-moon"></i>
                                <div>
                                    <strong>Modo Oscuro</strong>
                                    <p>Cambiar la apariencia del dashboard.</p>
                                </div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="darkModeToggle2" onclick="toggleDarkMode()">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Layout Grid */
    .profile-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
        align-items: start;
    }

    /* Sidebar Izquierda */
    .user-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }
    
    /* Avatar Styling */
    .avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
    }
    .user-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .user-avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        font-weight: bold;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .avatar-edit-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: var(--primary-color, #e74c3c);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        transition: transform 0.2s;
    }
    .avatar-edit-btn:hover { transform: scale(1.1); }

    /* Info Usuario */
    .user-name { font-size: 1.4rem; margin: 0 0 5px; color: #2c3e50; font-weight: 700; }
    .user-email { color: #7f8c8d; margin-bottom: 15px; font-size: 0.9rem; }
    .badge-role { background: #eaf3ff; color: #3498db; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }

    .user-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }
    .stat-box strong { display: block; font-size: 1.1rem; color: #2c3e50; }
    .stat-box span { font-size: 0.8rem; color: #95a5a6; }

    /* Menú Lateral */
    .profile-menu { list-style: none; padding: 0; margin: 0; }
    .profile-menu li { cursor: pointer; }
    .profile-menu li a {
        display: block;
        padding: 15px 20px;
        color: #5a6a7b;
        text-decoration: none;
        font-weight: 500;
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    .profile-menu li a i { margin-right: 10px; width: 20px; text-align: center; }
    .profile-menu li:hover a { background: #f8f9fa; color: var(--primary-color, #e74c3c); }
    .profile-menu li.active a { background: #fff8f8; color: var(--primary-color, #e74c3c); border-left-color: var(--primary-color, #e74c3c); }

    /* Formularios */
    .section-title {
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary-color, #e74c3c);
        padding-left: 10px;
    }
    .separator { margin: 30px 0; border: 0; border-top: 1px dashed #e0e0e0; }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .full-width { grid-column: span 2; }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: #fdfdfd;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: var(--primary-color, #e74c3c); background: #fff; outline: none; box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1); }

    .form-actions-right { text-align: right; margin-top: 20px; }

    /* Settings List */
    .setting-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
    .setting-item:last-child { border-bottom: none; }
    .setting-info { display: flex; align-items: center; gap: 15px; }
    .setting-info i { font-size: 1.2rem; color: #95a5a6; }
    .setting-info strong { display: block; color: #2c3e50; }
    .setting-info p { margin: 0; font-size: 0.85rem; color: #95a5a6; }

    /* Switch (Slider) */
    .switch { position: relative; display: inline-block; width: 50px; height: 26px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: var(--primary-color, #e74c3c); }
    input:checked + .slider:before { transform: translateX(24px); }

    /* Alertas */
    .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid transparent; }
    .alert-danger { background-color: #ffe6e6; border-color: #ffcccc; color: #e74c3c; }
    .alert-success { background-color: #e6ffe6; border-color: #ccffcc; color: #27ae60; }

    /* Responsive */
    @media (max-width: 900px) {
        .profile-layout { grid-template-columns: 1fr; }
        .form-grid { grid-template-columns: 1fr; }
        .full-width { grid-column: span 1; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Función para cambiar entre pestañas
    function switchTab(tabName, element) {
        // 1. Ocultar todos los contenidos
        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
            content.classList.remove('active');
        });

        // 2. Desactivar todos los ítems del menú
        document.querySelectorAll('.profile-menu li').forEach(li => {
            li.classList.remove('active');
        });

        // 3. Mostrar el contenido seleccionado
        document.getElementById('tab-' + tabName).style.display = 'block';
        setTimeout(() => {
            document.getElementById('tab-' + tabName).classList.add('active');
        }, 10);
        
        // 4. Activar el ítem del menú clickeado
        element.classList.add('active');
    }

    // Sincronizar modo oscuro
    document.addEventListener('DOMContentLoaded', function() {
        const profileToggle = document.getElementById('darkModeToggle2');
        if(localStorage.getItem('darkMode') === 'true' && profileToggle) {
            profileToggle.checked = true;
        }
    });
</script>
@endpush