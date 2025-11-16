@extends('layouts.dashboard')

@section('title', 'Gestión de Adultos Mayores - WasiQhari')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Gestión de Adultos Mayores</h1>
            <p>Administra y monitorea los casos registrados</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="abrirModalNuevo()">
                <i class="fas fa-plus"></i> Nuevo Registro
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filtros-section">
        <div class="filtros-grid">
            <div class="form-group">
                <label>Distrito</label>
                <select id="filtroDistrito" onchange="filtrarAdultos()">
                    <option value="">Todos los distritos</option>
                    <option value="Cusco">Cusco</option>
                    <option value="Wanchaq">Wanchaq</option>
                    <option value="San Sebastián">San Sebastián</option>
                    <option value="Santiago">Santiago</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Estado de Salud</label>
                <select id="filtroSalud" onchange="filtrarAdultos()">
                    <option value="">Todos los estados</option>
                    <option value="Bueno">Bueno</option>
                    <option value="Regular">Regular</option>
                    <option value="Malo">Malo</option>
                    <option value="Critico">Crítico</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Género</label>
                <select id="filtroGenero" onchange="filtrarAdultos()">
                    <option value="">Todos</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Estado Abandono</label>
                <select id="filtroAbandono" onchange="filtrarAdultos()">
                    <option value="">Todos</option>
                    <option value="Situación Calle">Situación Calle</option>
                    <option value="Total">Abandono Total</option>
                    <option value="Parcial">Abandono Parcial</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Lista de Adultos Mayores -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Edad</th>
                        <th>Distrito</th>
                        <th>Estado Salud</th>
                        <th>Actividad</th>
                        <th>Última Visita</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaAdultos">
                    @foreach($adultos as $adulto)
                    <tr data-adulto="{{ htmlspecialchars(json_encode($adulto), ENT_QUOTES, 'UTF-8') }}">
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ $adulto->sexo == 'M' ? '👴' : '👵' }}
                                </div>
                                <div class="user-details">
                                    <strong>{{ $adulto->nombres }} {{ $adulto->apellidos }}</strong>
                                    <small>DNI: {{ $adulto->dni ?: 'No tiene' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="edad-badge">{{ $adulto->edad }} años</span>
                        </td>
                        <td>{{ $adulto->distrito }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($adulto->estado_salud) }}">
                                {{ $adulto->estado_salud }}
                            </span>
                        </td>
                        <td>
                            <span class="actividad-tag">{{ $adulto->actividad_calle }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($adulto->fecha_registro)->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" onclick="verDetalle({{ $adulto->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon btn-edit" onclick="editarAdulto({{ $adulto->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon btn-delete" onclick="eliminarAdulto({{ $adulto->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn-icon btn-help" onclick="asignarAyuda({{ $adulto->id }})">
                                    <i class="fas fa-hands-helping"></i>
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

<!-- Modal para Nuevo/Editar Adulto -->
<div id="adultoModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3 id="modalTitulo">Nuevo Adulto Mayor</h3>
            <span class="close">&times;</span>
        </div>
        <form id="adultoForm" action="{{ route('adultos.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" placeholder="Ingrese DNI (opcional)">
                    </div>
                    
                    <div class="form-group">
                        <label for="apellidos">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombres">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sexo">Sexo *</label>
                        <select id="sexo" name="sexo" required>
                            <option value="">Seleccionar</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required onchange="calcularEdad()">
                    </div>
                    
                    <div class="form-group">
                        <label for="edad">Edad</label>
                        <input type="number" id="edad" name="edad" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="distrito">Distrito *</label>
                        <select id="distrito" name="distrito" required>
                            <option value="">Seleccionar</option>
                            <option value="Cusco">Cusco</option>
                            <option value="Wanchaq">Wanchaq</option>
                            <option value="San Sebastián">San Sebastián</option>
                            <option value="Santiago">Santiago</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="zona_ubicacion">Zona de Ubicación *</label>
                        <input type="text" id="zona_ubicacion" name="zona_ubicacion" required placeholder="Ej: Mercado Central, Plaza de Armas">
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <textarea id="direccion" name="direccion" placeholder="Dirección específica (opcional)"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" placeholder="Número de contacto (opcional)">
                    </div>
                    
                    <div class="form-group">
                        <label for="lee_escribe">Lee y Escribe *</label>
                        <select id="lee_escribe" name="lee_escribe" required>
                            <option value="">Seleccionar</option>
                            <option value="Si">Sí</option>
                            <option value="No">No</option>
                            <option value="Poco">Poco</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="nivel_estudio">Nivel de Estudio *</label>
                        <select id="nivel_estudio" name="nivel_estudio" required>
                            <option value="">Seleccionar</option>
                            <option value="Ninguno">Ninguno</option>
                            <option value="Primaria">Primaria</option>
                            <option value="Secundaria">Secundaria</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="apoyo_familiar">Apoyo Familiar *</label>
                        <select id="apoyo_familiar" name="apoyo_familiar" required>
                            <option value="">Seleccionar</option>
                            <option value="Ninguno">Ninguno</option>
                            <option value="Poco">Poco</option>
                            <option value="Ocasional">Ocasional</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="estado_abandono">Estado de Abandono *</label>
                        <select id="estado_abandono" name="estado_abandono" required>
                            <option value="">Seleccionar</option>
                            <option value="Situación Calle">Situación Calle</option>
                            <option value="Total">Abandono Total</option>
                            <option value="Parcial">Abandono Parcial</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="estado_salud">Estado de Salud *</label>
                        <select id="estado_salud" name="estado_salud" required>
                            <option value="">Seleccionar</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                            <option value="Critico">Crítico</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="actividad_calle">Actividad en Calle *</label>
                        <select id="actividad_calle" name="actividad_calle" required>
                            <option value="">Seleccionar</option>
                            <option value="Vende dulces">Vende dulces</option>
                            <option value="Pide limosna">Pide limosna</option>
                            <option value="Recicla">Recicla</option>
                            <option value="Vende artesanías">Vende artesanías</option>
                            <option value="Vende periódicos">Vende periódicos</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="necesidades">Necesidades Detectadas</label>
                        <textarea id="necesidades" name="necesidades" placeholder="Describa las necesidades específicas..."></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.filtros-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: var(--shadow);
}

.filtros-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.table-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f8f9fa;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: var(--dark-color);
    border-bottom: 2px solid #e9ecef;
}

.data-table td {
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
}

.data-table tr:hover {
    background: #f8f9fa;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.user-details strong {
    display: block;
    margin-bottom: 2px;
}

.user-details small {
    color: var(--text-light);
}

.edad-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-critico { background: #ffebee; color: #c62828; }
.status-malo { background: #fff3e0; color: #ef6c00; }
.status-regular { background: #e3f2fd; color: #1565c0; }
.status-bueno { background: #e8f5e8; color: #2e7d32; }

.actividad-tag {
    background: #f3e5f5;
    color: #7b1fa2;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-icon {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.btn-view { background: #e3f2fd; color: #1976d2; }
.btn-edit { background: #fff3e0; color: #ef6c00; }
.btn-delete { background: #ffebee; color: #c62828; }
.btn-help { background: #e8f5e8; color: #2e7d32; }

.btn-icon:hover {
    transform: translateY(-2px);
}

.modal-content.large {
    max-width: 800px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.text-muted {
    color: var(--text-light) !important;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .filtros-grid {
        grid-template-columns: 1fr;
    }
    
    .data-table {
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Variables globales
let adultoActual = null;
const modal = document.getElementById('adultoModal');
const form = document.getElementById('adultoForm');

// Funciones del modal
function abrirModalNuevo() {
    adultoActual = null;
    document.getElementById('modalTitulo').textContent = 'Nuevo Adulto Mayor';
    form.reset();
    modal.style.display = 'block';
}

function editarAdulto(id) {
    const fila = document.querySelector(`tr[data-adulto*="${id}"]`);
    if (fila) {
        try {
            adultoActual = JSON.parse(fila.getAttribute('data-adulto'));
            document.getElementById('modalTitulo').textContent = 'Editar Adulto Mayor';
            
            // Llenar formulario con datos existentes
            Object.keys(adultoActual).forEach(key => {
                const field = document.getElementById(key);
                if (field) {
                    field.value = adultoActual[key];
                }
            });
            
            modal.style.display = 'block';
        } catch (error) {
            console.error('Error al parsear datos del adulto:', error);
            Swal.fire('Error', 'No se pudieron cargar los datos del adulto mayor', 'error');
        }
    }
}

function verDetalle(id) {
    Swal.fire({
        title: 'Detalle del Adulto Mayor',
        text: 'Funcionalidad en desarrollo...',
        icon: 'info'
    });
}

function eliminarAdulto(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Simular eliminación
            Swal.fire(
                '¡Eliminado!',
                'El registro ha sido eliminado.',
                'success'
            );
        }
    });
}

function asignarAyuda(id) {
    Swal.fire({
        title: 'Asignar Ayuda',
        html: `
            <div class="ayuda-form">
                <div class="form-group">
                    <label>Tipo de Ayuda</label>
                    <select id="tipoAyuda" class="swal2-input">
                        <option value="visita">Visita de Acompañamiento</option>
                        <option value="alimentos">Entrega de Alimentos</option>
                        <option value="medicina">Atención Médica</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Prioridad</label>
                    <select id="prioridadAyuda" class="swal2-input">
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Asignar Ayuda',
        preConfirm: () => {
            return {
                tipo: document.getElementById('tipoAyuda').value,
                prioridad: document.getElementById('prioridadAyuda').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                '¡Ayuda Asignada!',
                'Se ha registrado la solicitud de ayuda.',
                'success'
            );
        }
    });
}

function calcularEdad() {
    const fechaNacimiento = new Date(document.getElementById('fecha_nacimiento').value);
    const hoy = new Date();
    const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    document.getElementById('edad').value = edad;
}

function filtrarAdultos() {
    const distrito = document.getElementById('filtroDistrito').value;
    const salud = document.getElementById('filtroSalud').value;
    const genero = document.getElementById('filtroGenero').value;
    const abandono = document.getElementById('filtroAbandono').value;
    
    const filas = document.querySelectorAll('#tablaAdultos tr');
    
    filas.forEach(fila => {
        try {
            const adulto = JSON.parse(fila.getAttribute('data-adulto'));
            let mostrar = true;
            
            if (distrito && adulto.distrito !== distrito) mostrar = false;
            if (salud && adulto.estado_salud !== salud) mostrar = false;
            if (genero && adulto.sexo !== genero) mostrar = false;
            if (abandono && adulto.estado_abandono !== abandono) mostrar = false;
            
            fila.style.display = mostrar ? '' : 'none';
        } catch (error) {
            console.error('Error al filtrar:', error);
        }
    });
}

// Cerrar modal
document.querySelector('.close').onclick = cerrarModal;
window.onclick = (event) => { 
    if (event.target == modal) cerrarModal(); 
}

function cerrarModal() {
    modal.style.display = 'none';
    adultoActual = null;
}

// Envío del formulario
form.onsubmit = (e) => {
    e.preventDefault();
    
    // Simular guardado
    Swal.fire({
        title: '¡Guardado!',
        text: adultoActual ? 'Registro actualizado correctamente' : 'Nuevo registro creado correctamente',
        icon: 'success',
        timer: 2000
    });
    
    cerrarModal();
};
</script>
@endpush