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
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error_form') || session('error_form_edit'))
        <div class="alert alert-danger">
            <strong>¡Error!</strong> {{ session('error_form') ?? session('error_form_edit') }}
            <ul>
                 @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            <h3>Adultos Mayores Registrados</h3>
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
                    <tbody>
                        @forelse($adultos as $adulto)
                            <tr id="fila-adulto-{{ $adulto->id }}"> <td>{{ $adulto->nombres }} {{ $adulto->apellidos }}</td>
                                <td>{{ $adulto->dni ?? 'N/A' }}</td>
                                <td>{{ $adulto->distrito }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($adulto->estado_salud) }}">
                                        {{ $adulto->estado_salud }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-riesgo-{{ strtolower($adulto->nivel_riesgo) }}">
                                        {{ $adulto->nivel_riesgo }}
                                    </span>
                                </td>
                                <td>{{ $adulto->fecha_registro->format('d/m/Y') }}</td>
                                <td>
                                    <button class="btn-action btn-ver" 
                                            data-id="{{ $adulto->id }}" 
                                            title="Ver / Editar">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-eliminar" 
                                            data-id="{{ $adulto->id }}" 
                                            data-name="{{ $adulto->nombres }} {{ $adulto->apellidos }}" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay adultos mayores registrados todavía.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-container">
                {{ $adultos->links() }}
            </div>
        </div>
    </div>

</div> <div id="modalAdulto" class="modal">
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
                    <div class="form-group">
                        <label for="nombres">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" required>
                    </div>
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" maxlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label for="edad">Edad *</label>
                        <input type="number" id="edad" name="edad" required min="60" readonly style="background-color: #f8f9fa; cursor: not-allowed;" title="La edad se calcula automáticamente">
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo *</label>
                        <select id="sexo" name="sexo" required>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="distrito">Distrito *</label>
                        <select id="distrito" name="distrito" required>
                            <option value="">Selecciona un distrito</option>
                            <option value="Cusco">Cusco</option>
                            <option value="Wanchaq">Wanchaq</option>
                            <option value="San Sebastián">San Sebastián</option>
                            <option value="Santiago">Santiago</option>
                            <option value="San Jerónimo">San Jerónimo</option>
                            <option value="Poroy">Poroy</option>
                            <option value="Saylla">Saylla</option>
                            <option value="Ccorca">Ccorca</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="zona_ubicacion">Zona Ubicación *</label>
                        <input type="text" id="zona_ubicacion" name="zona_ubicacion" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label for="lee_escribe">Lee y Escribe *</label>
                        <select id="lee_escribe" name="lee_escribe" required>
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                            <option value="Poco">Poco</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nivel_estudio">Nivel de Estudio *</label>
                        <select id="nivel_estudio" name="nivel_estudio" required>
                            <option value="Ninguno">Ninguno</option>
                            <option value="Primaria">Primaria</option>
                            <option value="Secundaria">Secundaria</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="apoyo_familiar">Apoyo Familiar *</label>
                        <select id="apoyo_familiar" name="apoyo_familiar" required>
                            <option value="Ninguno">Ninguno</option>
                            <option value="Poco">Poco</option>
                            <option value="Ocasional">Ocasional</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado_abandono">Estado de Abandono *</label>
                        <select id="estado_abandono" name="estado_abandono" required>
                            <option value="Total">Total</option>
                            <option value="Parcial">Parcial</option>
                            <option value="Situación Calle">Situación Calle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado_salud">Estado de Salud *</label>
                        <select id="estado_salud" name="estado_salud" required>
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                            <option value="Critico">Crítico</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="actividad_calle">Actividad en Calle *</label>
                        <select id="actividad_calle" name="actividad_calle" required>
                            <option value="Vende dulces">Vende dulces</option>
                            <option value="Pide limosna">Pide limosna</option>
                            <option value="Recicla">Recicla</option>
                            <option value="Vende artesanías">Vende artesanías</option>
                            <option value="Vende periódicos">Vende periódicos</option>
                            <option value="Vende frutas">Vende frutas</option>
                            <option value="Vende flores">Vende flores</option>
                            <option value="Vende empanadas">Vende empanadas</option>
                            <option value="Vende verduras">Vende verduras</option>
                            <option value="Ninguna">Ninguna</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="necesidades">Necesidades</label>
                        <textarea id="necesidades" name="necesidades"></textarea>
                    </div>
                     <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="lat">Latitud</label>
                        <input type="text" id="lat" name="lat" placeholder="-13.5226">
                    </div>
                    <div class="form-group">
                        <label for="lon">Longitud</label>
                        <input type="text" id="lon" name="lon" placeholder="-71.9673">
                    </div>
                    <div class="form-group">
                        <label for="nivel_riesgo">Nivel de Riesgo *</label>
                        <select id="nivel_riesgo" name="nivel_riesgo" required>
                            <option value="Bajo">Bajo</option>
                            <option value="Medio">Medio</option>
                            <option value="Alto">Alto</option>
                        </select>
                    </div>
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
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Referencias a Elementos ---
    const modal = document.getElementById('modalAdulto');
    const closeModal = document.getElementById('closeModal');
    const btnCancelarModal = document.getElementById('btnCancelarModal');
    const btnNuevoRegistro = document.getElementById('btnNuevoRegistro');
    const modalTitulo = document.getElementById('modalTitulo');
    const formAdulto = document.getElementById('formAdulto');
    const formMethod = document.getElementById('formMethod');
    const btnGuardar = document.getElementById('btnGuardar');
    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const edadInput = document.getElementById('edad');
    
    // Token CSRF (leído desde el <meta> tag en el layout)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // --- 1. ABRIR MODAL PARA NUEVO REGISTRO ---
    if(btnNuevoRegistro) {
        btnNuevoRegistro.addEventListener('click', function() {
            formAdulto.reset(); 
            formAdulto.action = "{{ route('adultos.store') }}"; 
            formMethod.value = "POST"; 
            modalTitulo.textContent = "Nuevo Adulto Mayor";
            btnGuardar.textContent = "Guardar Registro";
            document.getElementById('fecha_registro').value = new Date().toISOString().slice(0,10);
            modal.style.display = 'block';
        });
    }

    // --- 2. CERRAR MODAL ---
    function cerrarModal() {
        if(modal) {
            modal.style.display = 'none';
            formAdulto.reset();
        }
    }
    if(closeModal) closeModal.addEventListener('click', cerrarModal);
    if(btnCancelarModal) btnCancelarModal.addEventListener('click', cerrarModal);
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            cerrarModal();
        }
    });

    // --- 3. ABRIR MODAL PARA EDITAR (Botón del Ojo) ---
    document.querySelectorAll('.btn-ver').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            // Usamos fetch para traer los datos del adulto
            fetch(`/dashboard/adultos/${id}`)
                .then(response => {
                    // ¡Importante! Verificamos si la respuesta fue exitosa
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Rellenamos el formulario con los datos
                    formAdulto.reset();
                    document.getElementById('nombres').value = data.nombres;
                    document.getElementById('apellidos').value = data.apellidos;
                    document.getElementById('dni').value = data.dni;
                    document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento.split('T')[0];
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
                    
                    // ¡Calculamos la edad al cargar!
                    calcularEdad(); 
                    
                    formAdulto.action = `/dashboard/adultos/${id}`; 
                    formMethod.value = "PUT"; 
                    modalTitulo.textContent = "Editar Adulto Mayor";
                    btnGuardar.textContent = "Guardar Cambios";
                    
                    modal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error en Fetch (Ojo):', error);
                    Swal.fire('Error', 'No se pudieron cargar los datos. Asegúrate de que las rutas (routes/web.php) estén correctas.', 'error');
                });
        });
    });

    // --- 4. ELIMINAR REGISTRO (Botón del Tacho) ---
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            Swal.fire({
                title: `¿Estás seguro?`,
                text: `¡No podrás revertir esto! Se eliminará a ${name}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Sí, ¡bórralo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // ===== ¡¡AQUÍ ESTÁ LA CORRECCIÓN!! =====
                    // Usamos la variable 'csrfToken' definida al inicio,
                    // en lugar de '{{ csrf_token() }}'
                    fetch(`/dashboard/adultos/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken, 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire(
                                '¡Eliminado!',
                                data.message,
                                'success'
                            );
                            document.getElementById(`fila-adulto-${id}`).remove();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error en Fetch (Tacho):', error);
                        Swal.fire('Error', 'Ocurrió un error al eliminar. Revisa la consola y tus rutas (routes/web.php).', 'error');
                    });
                }
            });
        });
    });

    // --- 5. CALCULAR EDAD AUTOMÁTICAMENTE ---
    function calcularEdad() {
        const fechaNac = fechaNacimientoInput.value;
        if(fechaNac) {
            const hoy = new Date();
            const nacimiento = new Date(fechaNac);
            let edad = hoy.getFullYear() - nacimiento.getFullYear();
            const m = hoy.getMonth() - nacimiento.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
                edad--;
            }
            edadInput.value = edad >= 0 ? edad : 0;
        } else {
            edadInput.value = '';
        }
    }

    if(fechaNacimientoInput) {
        fechaNacimientoInput.addEventListener('change', calcularEdad);
    }

    // --- 6. MOSTRAR FORMULARIO SI HAY ERRORES DE VALIDACIÓN ---
    @if(session('error_form') || session('error_form_edit'))
        if(modal) {
            modal.style.display = 'block';
            
            // Si el error fue editando, reconfiguramos el modal
            @if(session('error_form_edit'))
                modalTitulo.textContent = "Editar Adulto Mayor";
                btnGuardar.textContent = "Guardar Cambios";
                formMethod.value = "PUT";
                // Re-calculamos la edad basada en el 'old' input
                calcularEdad(); 
                
                // ¡Importante! Re-escribimos la URL de acción correcta
                // (Necesitamos el ID del 'old input', pero Laravel no lo facilita. 
                // Esta es una forma de recuperarlo si el form falló la validación)
                @if(old('id')) 
                    formAdulto.action = `/dashboard/adultos/{{ old('id') }}`;
                @else
                    // Si no podemos recuperar el ID, al menos avisamos.
                    console.warn('No se pudo recuperar el ID para la acción de editar. El formulario puede fallar.');
                @endif
            @endif
        }
    @endif

});
</script>
@endpush