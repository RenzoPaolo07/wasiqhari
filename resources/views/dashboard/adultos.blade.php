@extends('layouts.dashboard')

@section('title', $title ?? 'Gestión de Adultos Mayores')

@section('content')
<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Gestión de Adultos Mayores</h1>
            <p>Registra, edita y administra a los beneficiarios.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="btnNuevoRegistro">
                <i class="fas fa-plus"></i> Nuevo Registro
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error_form') || session('error_form_edit'))
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Revisa los campos.
             @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </div>
    @endif

    <div class="content-card">
        <div class="card-header search-header">
            <h3>Adultos Mayores Registrados</h3>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="liveSearch" placeholder="Buscar por nombre, DNI o distrito..." class="search-input">
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>DNI</th>
                            <th>Distrito</th>
                            <th>Estado Salud</th>
                            <th>Riesgo</th>
                            <th>F. Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaAdultosBody">
                        @include('dashboard.partials.tabla_adultos')
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-container" id="paginationContainer">
                {{ $adultos->links() }}
            </div>
        </div>
    </div>

</div>

<div id="modalAdulto" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3 id="modalTitulo">Nuevo Adulto Mayor</h3>
            <span class="close" id="closeModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="formAdulto" action="{{ route('adultos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="form-grid">
                    <div class="form-group"><label>Nombres *</label><input type="text" id="nombres" name="nombres" required></div>
                    <div class="form-group"><label>Apellidos *</label><input type="text" id="apellidos" name="apellidos" required></div>
                    <div class="form-group"><label>DNI</label><input type="text" id="dni" name="dni" maxlength="8"></div>
                    <div class="form-group"><label>Fecha Nacimiento *</label><input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required></div>
                    <div class="form-group"><label>Edad *</label><input type="number" id="edad" name="edad" required min="60" readonly style="background:#f8f9fa;"></div>
                    <div class="form-group"><label>Sexo *</label><select id="sexo" name="sexo" required><option value="M">Masculino</option><option value="F">Femenino</option></select></div>
                    
                    <div class="form-group">
                        <label>Distrito *</label>
                        <select id="distrito" name="distrito" required>
                            <option value="">Selecciona...</option>
                            @foreach(['Cusco', 'Wanchaq', 'San Sebastián', 'Santiago', 'San Jerónimo', 'Poroy', 'Saylla', 'Ccorca'] as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group"><label>Zona Ubicación *</label><input type="text" id="zona_ubicacion" name="zona_ubicacion" required></div>
                    <div class="form-group"><label>Dirección</label><input type="text" id="direccion" name="direccion"></div>
                    <div class="form-group"><label>Teléfono</label><input type="text" id="telefono" name="telefono" maxlength="9"></div>
                    <div class="form-group"><label>Lee y Escribe *</label><select id="lee_escribe" name="lee_escribe" required><option value="Si">Si</option><option value="No">No</option><option value="Poco">Poco</option></select></div>
                    <div class="form-group"><label>Nivel Estudio *</label><select id="nivel_estudio" name="nivel_estudio" required><option value="Ninguno">Ninguno</option><option value="Primaria">Primaria</option><option value="Secundaria">Secundaria</option></select></div>
                    <div class="form-group"><label>Apoyo Familiar *</label><select id="apoyo_familiar" name="apoyo_familiar" required><option value="Ninguno">Ninguno</option><option value="Poco">Poco</option><option value="Ocasional">Ocasional</option></select></div>
                    <div class="form-group"><label>Estado Abandono *</label><select id="estado_abandono" name="estado_abandono" required><option value="Total">Total</option><option value="Parcial">Parcial</option><option value="Situación Calle">Situación Calle</option></select></div>
                    <div class="form-group"><label>Estado Salud *</label><select id="estado_salud" name="estado_salud" required><option value="Bueno">Bueno</option><option value="Regular">Regular</option><option value="Malo">Malo</option><option value="Critico">Crítico</option></select></div>
                    <div class="form-group"><label>Actividad *</label><input type="text" id="actividad_calle" name="actividad_calle" value="Otro" required></div>
                    <div class="form-group"><label>Necesidades</label><textarea id="necesidades" name="necesidades"></textarea></div>
                    <div class="form-group"><label>Observaciones</label><textarea id="observaciones" name="observaciones"></textarea></div>
                    <div class="form-group"><label>Latitud</label><input type="text" id="lat" name="lat" placeholder="-13.5226"></div>
                    <div class="form-group"><label>Longitud</label><input type="text" id="lon" name="lon" placeholder="-71.9673"></div>
                    <div class="form-group"><label>Riesgo *</label><select id="nivel_riesgo" name="nivel_riesgo" required><option value="Bajo">Bajo</option><option value="Medio">Medio</option><option value="Alto">Alto</option></select></div>
                </div>
                
                <input type="hidden" id="fecha_registro" name="fecha_registro">

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="btnCancelarModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header.search-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .search-container { position: relative; max-width: 300px; width: 100%; }
    .search-input { width: 100%; padding: 10px 15px 10px 40px; border: 1px solid #e0e0e0; border-radius: 20px; font-size: 0.9rem; transition: all 0.3s; }
    .search-input:focus { outline: none; border-color: var(--primary-color, #e74c3c); box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1); }
    .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #95a5a6; }
    .btn-action.btn-credencial { color: #8e44ad; }
    .btn-action.btn-credencial:hover { background: #f3e5f5; color: #6c3483; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const modal = document.getElementById('modalAdulto');
    const formAdulto = document.getElementById('formAdulto');
    const btnNuevo = document.getElementById('btnNuevoRegistro');
    const searchInput = document.getElementById('liveSearch');
    const tableBody = document.getElementById('tablaAdultosBody');
    const paginationContainer = document.getElementById('paginationContainer');
    let timeout = null;

    // --- BUSCADOR EN VIVO ---
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(timeout);
            const term = this.value;
            timeout = setTimeout(() => {
                fetch(`{{ route('adultos') }}?search=${term}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    attachButtonEvents(); // Re-conectar eventos
                    
                    // Actualizar paginación si viene en el HTML parcial
                    const hiddenPag = document.getElementById('pagination-links-hidden');
                    if(hiddenPag && paginationContainer) {
                        paginationContainer.innerHTML = hiddenPag.innerHTML;
                    }
                });
            }, 300);
        });
    }

    // --- EVENTOS ---
    function attachButtonEvents() {
        // Ver / Editar
        document.querySelectorAll('.btn-ver').forEach(button => {
            button.addEventListener('click', function() {
                cargarDatosAdulto(this.getAttribute('data-id'));
            });
        });

        // Eliminar
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', function() {
                confirmarEliminacion(this.getAttribute('data-id'), this.getAttribute('data-name'));
            });
        });
    }

    // Cargar datos
    function cargarDatosAdulto(id) {
        fetch(`/dashboard/adultos/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('nombres').value = data.nombres;
                document.getElementById('apellidos').value = data.apellidos;
                document.getElementById('dni').value = data.dni;
                document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento.split('T')[0];
                
                // Trigger edad
                const evt = new Event('change');
                document.getElementById('fecha_nacimiento').dispatchEvent(evt);

                document.getElementById('sexo').value = data.sexo;
                document.getElementById('distrito').value = data.distrito;
                document.getElementById('zona_ubicacion').value = data.zona_ubicacion;
                document.getElementById('direccion').value = data.direccion;
                document.getElementById('telefono').value = data.telefono;
                document.getElementById('lee_escribe').value = data.lee_escribe;
                document.getElementById('nivel_estudio').value = data.nivel_estudio;
                document.getElementById('apoyo_familiar').value = data.apoyo_familiar;
                document.getElementById('estado_abandono').value = data.estado_abandono;
                document.getElementById('estado_salud').value = data.estado_salud;
                document.getElementById('actividad_calle').value = data.actividad_calle;
                document.getElementById('necesidades').value = data.necesidades;
                document.getElementById('observaciones').value = data.observaciones;
                document.getElementById('lat').value = data.lat;
                document.getElementById('lon').value = data.lon;
                document.getElementById('nivel_riesgo').value = data.nivel_riesgo;
                document.getElementById('fecha_registro').value = data.fecha_registro.split('T')[0];

                formAdulto.action = `/dashboard/adultos/${id}`;
                document.getElementById('formMethod').value = "PUT";
                document.getElementById('modalTitulo').textContent = "Editar Adulto Mayor";
                document.getElementById('btnGuardar').textContent = "Guardar Cambios";
                modal.style.display = 'block';
            });
    }

    // Eliminar
    function confirmarEliminacion(id, name) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        Swal.fire({
            title: `¿Eliminar a ${name}?`, text: "No se puede deshacer.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#e74c3c', cancelButtonColor: '#95a5a6', confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/dashboard/adultos/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
                }).then(r => r.json()).then(data => {
                    if(data.success) {
                        Swal.fire('Eliminado', '', 'success');
                        document.getElementById(`fila-adulto-${id}`).remove();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    // Abrir Modal
    if(btnNuevo) {
        btnNuevo.addEventListener('click', () => {
            formAdulto.reset();
            formAdulto.action = "{{ route('adultos.store') }}";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('modalTitulo').textContent = "Nuevo Adulto Mayor";
            document.getElementById('btnGuardar').textContent = "Guardar Registro";
            document.getElementById('fecha_registro').value = new Date().toISOString().slice(0,10);
            modal.style.display = 'block';
        });
    }

    // Cerrar Modal
    document.querySelectorAll('#closeModal, #btnCancelarModal').forEach(el => {
        el.addEventListener('click', () => modal.style.display = 'none');
    });
    window.addEventListener('click', e => { if(e.target == modal) modal.style.display = 'none'; });

    // Calcular Edad
    const fechaInput = document.getElementById('fecha_nacimiento');
    if(fechaInput) {
        fechaInput.addEventListener('change', function() {
            const hoy = new Date();
            const nac = new Date(this.value);
            let edad = hoy.getFullYear() - nac.getFullYear();
            const m = hoy.getMonth() - nac.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
            document.getElementById('edad').value = edad;
        });
    }

    attachButtonEvents();
});
</script>
@endpush