@extends('layouts.dashboard')

@section('title', 'Gestión de Voluntarios - WasiQhari')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Gestión de Voluntarios - AyniConnect</h1>
            <p>Administra la red solidaria de voluntarios</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="abrirModalNuevoVoluntario()">
                <i class="fas fa-user-plus"></i> Nuevo Voluntario
            </button>
        </div>
    </div>

    <!-- Estadísticas de Voluntarios -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ count($voluntarios) }}</h3>
                <p>Total Voluntarios</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ count(array_filter($voluntarios->toArray(), fn($v) => $v['estado'] === 'Activo')) }}</h3>
                <p>Voluntarios Activos</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-info">
                <h3>4</h3>
                <p>Distritos Cubiertos</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-hands-helping"></i>
            </div>
            <div class="stat-info">
                <h3>156</h3>
                <p>Visitas Realizadas</p>
            </div>
        </div>
    </div>

    <!-- Lista de Voluntarios -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Voluntario</th>
                        <th>Contacto</th>
                        <th>Distrito</th>
                        <th>Habilidades</th>
                        <th>Disponibilidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voluntarios as $voluntario)
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-details">
                                    <strong>Voluntario {{ $voluntario->id }}</strong>
                                    <small>Registrado: {{ \Carbon\Carbon::parse($voluntario->fecha_registro)->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div><i class="fas fa-phone"></i> {{ $voluntario->telefono ?: 'No especificado' }}</div>
                                <div><i class="fas fa-map-marker-alt"></i> {{ $voluntario->distrito }}</div>
                            </div>
                        </td>
                        <td>{{ $voluntario->distrito }}</td>
                        <td>
                            <div class="skills-tags">
                                @php
                                    $habilidades = explode(',', $voluntario->habilidades);
                                @endphp
                                @foreach($habilidades as $habilidad)
                                <span class="skill-tag">{{ trim($habilidad) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <span class="disponibilidad-badge">{{ $voluntario->disponibilidad }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ strtolower($voluntario->estado) }}">
                                {{ $voluntario->estado }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" onclick="verPerfilVoluntario({{ $voluntario->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon btn-edit" onclick="editarVoluntario({{ $voluntario->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon btn-assign" onclick="asignarCaso({{ $voluntario->id }})">
                                    <i class="fas fa-tasks"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Nuevo Voluntario -->
<div id="voluntarioModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo Voluntario</h3>
            <span class="close">&times;</span>
        </div>
        <form id="voluntarioForm">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="vol_nombre">Nombre Completo *</label>
                        <input type="text" id="vol_nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="vol_email">Email *</label>
                        <input type="email" id="vol_email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="vol_telefono">Teléfono *</label>
                        <input type="tel" id="vol_telefono" name="telefono" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="vol_distrito">Distrito *</label>
                        <select id="vol_distrito" name="distrito" required>
                            <option value="">Seleccionar</option>
                            <option value="Cusco">Cusco</option>
                            <option value="Wanchaq">Wanchaq</option>
                            <option value="San Sebastián">San Sebastián</option>
                            <option value="Santiago">Santiago</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="vol_direccion">Dirección</label>
                        <input type="text" id="vol_direccion" name="direccion" placeholder="Dirección completa">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="vol_habilidades">Habilidades *</label>
                        <div class="skills-selector">
                            <label class="skill-checkbox">
                                <input type="checkbox" name="habilidades[]" value="Acompañamiento"> Acompañamiento
                            </label>
                            <label class="skill-checkbox">
                                <input type="checkbox" name="habilidades[]" value="Apoyo emocional"> Apoyo emocional
                            </label>
                            <label class="skill-checkbox">
                                <input type="checkbox" name="habilidades[]" value="Entrega de alimentos"> Entrega de alimentos
                            </label>
                            <label class="skill-checkbox">
                                <input type="checkbox" name="habilidades[]" value="Atención médica"> Atención médica
                            </label>
                            <label class="skill-checkbox">
                                <input type="checkbox" name="habilidades[]" value="Logística"> Logística
                            </label>
                            <label class="skill-checkbox">
                                <input type="checkbox" name="habilidades[]" value="Coordinación"> Coordinación
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="vol_disponibilidad">Disponibilidad *</label>
                        <select id="vol_disponibilidad" name="disponibilidad" required>
                            <option value="">Seleccionar</option>
                            <option value="Mañanas">Mañanas (8:00 - 12:00)</option>
                            <option value="Tardes">Tardes (14:00 - 18:00)</option>
                            <option value="Noches">Noches (18:00 - 22:00)</option>
                            <option value="Fines de semana">Fines de semana</option>
                            <option value="Flexible">Flexible</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="vol_zona">Zona de Cobertura *</label>
                        <input type="text" id="vol_zona" name="zona_cobertura" required placeholder="Ej: Cusco, Wanchaq">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalVoluntario()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Voluntario</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.skills-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.skill-tag {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
}

.disponibilidad-badge {
    background: #fff3e0;
    color: #ef6c00;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.contact-info div {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.contact-info i {
    width: 16px;
    margin-right: 8px;
    color: var(--text-light);
}

.skills-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 8px;
}

.skill-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
}

.skill-checkbox:hover {
    background: #e9ecef;
}

.skill-checkbox input[type="checkbox"] {
    margin: 0;
}

.voluntario-perfil {
    text-align: center;
}

.avatar-large {
    width: 80px;
    height: 80px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 15px;
}

.perfil-info {
    text-align: left;
    margin-top: 15px;
}

.perfil-info p {
    margin: 8px 0;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.perfil-info strong {
    color: var(--dark-color);
}

.stat-icon.info {
    background: #3498db;
}

.btn-assign {
    background: #e3f2fd;
    color: #1976d2;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Funciones para voluntarios
function abrirModalNuevoVoluntario() {
    document.getElementById('voluntarioModal').style.display = 'block';
}

function cerrarModalVoluntario() {
    document.getElementById('voluntarioModal').style.display = 'none';
}

function verPerfilVoluntario(id) {
    Swal.fire({
        title: 'Perfil del Voluntario',
        html: `
            <div class="voluntario-perfil">
                <div class="perfil-header">
                    <div class="avatar-large">👤</div>
                    <h4>Voluntario ${id}</h4>
                </div>
                <div class="perfil-info">
                    <p><strong>Distrito:</strong> Cusco</p>
                    <p><strong>Disponibilidad:</strong> Tardes</p>
                    <p><strong>Estado:</strong> Activo</p>
                    <p><strong>Visitas realizadas:</strong> 12</p>
                </div>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
}

function editarVoluntario(id) {
    Swal.fire({
        title: 'Editar Voluntario',
        text: 'Funcionalidad en desarrollo...',
        icon: 'info'
    });
}

function asignarCaso(id) {
    Swal.fire({
        title: 'Asignar Caso a Voluntario',
        html: `
            <div class="asignacion-form">
                <div class="form-group">
                    <label>Seleccionar Adulto Mayor</label>
                    <select class="swal2-input">
                        <option value="">Seleccionar adulto mayor...</option>
                        <option value="1">Martina Quispe</option>
                        <option value="2">Eulogio Mamani</option>
                        <option value="3">Simeona Huaman</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipo de Asignación</label>
                    <select class="swal2-input">
                        <option value="visita">Visita única</option>
                        <option value="seguimiento">Seguimiento continuo</option>
                        <option value="emergencia">Caso de emergencia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha límite</label>
                    <input type="date" class="swal2-input">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Asignar Caso',
        preConfirm: () => {
            return {
                adulto: document.querySelector('.swal2-input').value,
                tipo: document.querySelectorAll('.swal2-input')[1].value,
                fecha: document.querySelectorAll('.swal2-input')[2].value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                '¡Caso Asignado!',
                'El voluntario ha sido asignado exitosamente.',
                'success'
            );
        }
    });
}

// Cerrar modal voluntario
document.querySelector('#voluntarioModal .close').onclick = cerrarModalVoluntario;
window.onclick = (event) => { 
    if (event.target == document.getElementById('voluntarioModal')) {
        cerrarModalVoluntario();
    }
}

// Envío del formulario de voluntario
document.getElementById('voluntarioForm').onsubmit = (e) => {
    e.preventDefault();
    
    Swal.fire({
        title: '¡Voluntario Registrado!',
        text: 'El voluntario ha sido registrado exitosamente en el sistema.',
        icon: 'success',
        timer: 2000
    });
    
    cerrarModalVoluntario();
};
</script>
@endpush