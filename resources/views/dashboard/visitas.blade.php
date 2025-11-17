@extends('layouts.dashboard')

@section('title', $title ?? 'Gestión de Visitas')

@section('content')
<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Gestión de Visitas</h1>
            <p>Registra y monitorea las visitas con evidencia fotográfica.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="btnNuevaVisita">
                <i class="fas fa-camera"></i> Nueva Visita
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error_form') || session('error_form_edit'))
        <div class="alert alert-danger">
            <strong>¡Atención!</strong> Revisa el formulario.
             @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            <h3>Historial de Visitas</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Adulto Mayor</th>
                            <th>Voluntario</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Evidencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visitas as $visita)
                            <tr id="fila-visita-{{ $visita->id }}">
                                <td>
                                    <strong>{{ $visita->adultoMayor->nombres ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $visita->adultoMayor->apellidos ?? '' }}</small>
                                </td>
                                <td>{{ $visita->voluntario->user->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $visita->fecha_visita->format('d/m/Y') }}<br>
                                    <small>{{ $visita->fecha_visita->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($visita->emergencia)
                                        <span class="badge badge-critico">Emergencia</span>
                                    @else
                                        <span class="badge badge-regular">{{ $visita->tipo_visita ?? 'Rutina' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($visita->foto_evidencia)
                                        <a href="{{ asset('storage/'.$visita->foto_evidencia) }}" target="_blank" class="link-foto">
                                            <i class="fas fa-image"></i> Ver Foto
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn-action btn-ver" data-id="{{ $visita->id }}" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-eliminar" data-id="{{ $visita->id }}" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No hay visitas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-container">{{ $visitas->links() }}</div>
        </div>
    </div>
</div>

<div id="modalVisita" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3 id="modalTitulo">Registrar Nueva Visita</h3>
            <span class="close" id="closeModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="formVisita" action="{{ route('visitas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="adulto_id">Adulto Mayor *</label>
                        <select id="adulto_id" name="adulto_id" required>
                            <option value="">Selecciona...</option>
                            @foreach($adultos as $adulto)
                                <option value="{{ $adulto->id }}">{{ $adulto->nombres }} {{ $adulto->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="voluntario_id">Voluntario *</label>
                        <select id="voluntario_id" name="voluntario_id" required>
                            <option value="">Selecciona...</option>
                            @foreach($voluntarios as $voluntario)
                                <option value="{{ $voluntario->id }}">{{ $voluntario->user->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_visita">Fecha y Hora *</label>
                        <input type="datetime-local" id="fecha_visita" name="fecha_visita" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_visita">Tipo de Visita</label>
                        <select id="tipo_visita" name="tipo_visita">
                            <option value="Acompañamiento">Acompañamiento</option>
                            <option value="Entrega de alimentos">Entrega de alimentos</option>
                            <option value="Atención médica">Atención médica</option>
                            <option value="Apoyo emocional">Apoyo emocional</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado_emocional">Estado Emocional</label>
                        <select id="estado_emocional" name="estado_emocional">
                             <option value="Estable">Estable</option>
                            <option value="Triste">Triste</option>
                            <option value="Ansioso">Ansioso</option>
                            <option value="Eufórico">Eufórico</option>
                            <option value="Deprimido">Deprimido</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado_fisico">Estado Físico</label>
                        <select id="estado_fisico" name="estado_fisico">
                             <option value="Regular">Regular</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Malo">Malo</option>
                            <option value="Crítico">Crítico</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="emergencia">¿Emergencia?</label>
                        <select id="emergencia" name="emergencia" style="color: #e74c3c; font-weight: bold;">
                            <option value="0">No</option>
                            <option value="1">¡SÍ!</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="grid-column: span 2;">
                         <label for="foto_evidencia"><i class="fas fa-camera"></i> Foto de Evidencia</label>
                         <input type="file" id="foto_evidencia" name="foto_evidencia" accept="image/*" onchange="previewImage(this)">
                         
                         <div id="imagePreviewContainer" style="display: none; margin-top: 10px; text-align: center;">
                             <img id="imagePreview" src="" alt="Previsualización" style="max-height: 150px; border-radius: 10px; border: 2px solid #eee;">
                         </div>
                    </div>
                    
                    <div class="form-group" style="grid-column: span 3;">
                        <label for="observaciones">Observaciones / Notas</label>
                        <textarea id="observaciones" name="observaciones" rows="2"></textarea>
                    </div>

                    <div class="form-group" style="grid-column: span 3;">
                        <label for="necesidades_detectadas">Necesidades Detectadas</label>
                        <textarea id="necesidades_detectadas" name="necesidades_detectadas" rows="2"></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="btnCancelarModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Visita</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Estilos extra para esta vista */
.link-foto { color: #3498db; font-weight: 500; text-decoration: none; }
.link-foto:hover { text-decoration: underline; }
.text-muted { color: #7f8c8d; font-size: 0.85rem; }
</style>
@endpush

@push('scripts')
<script>
// Función para previsualizar imagen
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalVisita');
    const btnNueva = document.getElementById('btnNuevaVisita');
    const form = document.getElementById('formVisita');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Abrir Modal Nuevo
    btnNueva.addEventListener('click', function() {
        form.reset();
        form.action = "{{ route('visitas.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('modalTitulo').textContent = "Registrar Nueva Visita";
        document.getElementById('imagePreviewContainer').style.display = 'none';
        
        // Fecha actual
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('fecha_visita').value = now.toISOString().slice(0,16);
        
        modal.style.display = 'block';
    });

    // Cerrar Modal
    document.querySelectorAll('#closeModal, #btnCancelarModal').forEach(el => {
        el.addEventListener('click', () => modal.style.display = 'none');
    });

    // Ver / Editar
    document.querySelectorAll('.btn-ver').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            fetch(`/dashboard/visitas/${id}`)
                .then(r => r.json())
                .then(data => {
                    form.reset();
                    document.getElementById('adulto_id').value = data.adulto_id;
                    document.getElementById('voluntario_id').value = data.voluntario_id;
                    document.getElementById('fecha_visita').value = data.fecha_visita.slice(0,16);
                    
                    // Asignar valores exactos del ENUM
                    document.getElementById('tipo_visita').value = data.tipo_visita;
                    document.getElementById('estado_emocional').value = data.estado_emocional;
                    document.getElementById('estado_fisico').value = data.estado_fisico;
                    
                    document.getElementById('emergencia').value = data.emergencia ? 1 : 0;
                    document.getElementById('observaciones').value = data.observaciones;
                    document.getElementById('necesidades_detectadas').value = data.necesidades_detectadas;
                    
                    // Mostrar foto existente
                    const container = document.getElementById('imagePreviewContainer');
                    const preview = document.getElementById('imagePreview');
                    if(data.foto_url) {
                        preview.src = data.foto_url;
                        container.style.display = 'block';
                    } else {
                        container.style.display = 'none';
                    }

                    form.action = `/dashboard/visitas/${id}`;
                    document.getElementById('formMethod').value = "PUT";
                    document.getElementById('modalTitulo').textContent = "Editar Detalles de Visita";
                    modal.style.display = 'block';
                })
                .catch(e => Swal.fire('Error', 'No se cargaron los datos', 'error'));
        });
    });

    // Eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Borrar visita?', text: "Se eliminará la foto también.",
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Sí, borrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dashboard/visitas/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
                    }).then(r => r.json()).then(data => {
                        if(data.success) {
                            document.getElementById(`fila-visita-${id}`).remove();
                            Swal.fire('Borrado', '', 'success');
                        }
                    });
                }
            });
        });
    });
});
</script>
@endpush