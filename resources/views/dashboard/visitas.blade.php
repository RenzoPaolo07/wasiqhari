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
                            <th>Estado Físico</th> <th>Evidencia</th>
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
                                    @php
                                        $color = 'secondary';
                                        if($visita->estado_fisico == 'Bueno') $color = 'success';
                                        if($visita->estado_fisico == 'Regular') $color = 'warning';
                                        if($visita->estado_fisico == 'Malo') $color = 'orange';
                                        if($visita->estado_fisico == 'Crítico' || $visita->estado_fisico == 'Critico') $color = 'danger';
                                    @endphp
                                    
                                    <span class="badge badge-{{ $color }}">
                                        {{ $visita->estado_fisico ?? 'Regular' }}
                                    </span>

                                    {{-- AQUÍ APARECE EL MENSAJE DEL DOCTOR --}}
                                    @if($visita->recomendacion_ia)
                                        <div style="margin-top: 5px;">
                                            <small style="font-size: 0.75rem; display: inline-block; 
                                                color: {{ str_contains($visita->recomendacion_ia, '⚠️') ? '#c0392b' : '#27ae60' }}; 
                                                font-weight: bold; background: #fff; padding: 4px 6px; border-radius: 4px; border: 1px solid #ddd; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                                {{ $visita->recomendacion_ia }}
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                
                                <td>
                                    @if($visita->foto_evidencia)
                                        <a href="{{ asset('storage/'.$visita->foto_evidencia) }}" target="_blank" class="link-foto">
                                            <i class="fas fa-image"></i> Ver
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <button class="btn-action btn-ver" data-id="{{ $visita->id }}" title="Ver Detalles y Chat">
                                        <i class="fas fa-comments"></i>
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
            <h3 id="modalTitulo">Detalles de Visita</h3>
            <span class="close" id="closeModal">&times;</span>
        </div>
        
        <div class="modal-tabs">
            <button class="tab-btn active" onclick="switchModalTab('detalles')">📝 Detalles</button>
            <button class="tab-btn" onclick="switchModalTab('chat')">💬 Chat de Seguimiento</button>
        </div>

        <div class="modal-body">
            
            <div id="tab-detalles" class="tab-pane active">
                <form id="formVisita" action="{{ route('visitas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Adulto Mayor *</label>
                            <select id="adulto_id" name="adulto_id" required>
                                <option value="">Selecciona...</option>
                                @foreach($adultos as $adulto)
                                    <option value="{{ $adulto->id }}">{{ $adulto->nombres }} {{ $adulto->apellidos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Voluntario *</label>
                            <select id="voluntario_id" name="voluntario_id" required>
                                <option value="">Selecciona...</option>
                                @foreach($voluntarios as $voluntario)
                                    <option value="{{ $voluntario->id }}">{{ $voluntario->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fecha y Hora *</label>
                            <input type="datetime-local" id="fecha_visita" name="fecha_visita" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Tipo de Visita</label>
                            <select id="tipo_visita" name="tipo_visita">
                                <option value="Acompañamiento">Acompañamiento</option>
                                <option value="Entrega de alimentos">Entrega de alimentos</option>
                                <option value="Atención médica">Atención médica</option>
                                <option value="Apoyo emocional">Apoyo emocional</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Estado Físico (Salud) *</label>
                            <select id="estado_fisico" name="estado_fisico" required style="border: 2px solid #3498db;">
                                <option value="Bueno">🟢 Bueno (Estable)</option>
                                <option value="Regular">🟡 Regular (Molestias leves)</option>
                                <option value="Malo">🟠 Malo (Requiere atención)</option>
                                <option value="Critico">🔴 Crítico (Urgencia)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Estado Emocional</label>
                            <select id="estado_emocional" name="estado_emocional">
                                <option value="Estable">Estable</option>
                                <option value="Triste">Triste</option>
                                <option value="Ansioso">Ansioso</option>
                                <option value="Eufórico">Eufórico</option>
                                <option value="Deprimido">Deprimido</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Emergencia?</label>
                            <select id="emergencia" name="emergencia" style="color: #e74c3c; font-weight: bold;">
                                <option value="0">No</option>
                                <option value="1">¡SÍ!</option>
                            </select>
                        </div>

                        <div class="form-group" style="grid-column: span 2;">
                             <label><i class="fas fa-camera"></i> Foto de Evidencia</label>
                             <input type="file" id="foto_evidencia" name="foto_evidencia" accept="image/*" onchange="previewImage(this)">
                             <div id="imagePreviewContainer" style="display: none; margin-top: 10px; text-align: center;">
                                 <img id="imagePreview" src="" alt="Previsualización" style="max-height: 150px; border-radius: 10px; border: 2px solid #eee;">
                             </div>
                        </div>
                        
                        <div class="form-group" style="grid-column: span 3;">
                            <label>Observaciones</label>
                            <textarea id="observaciones" name="observaciones" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="btnCancelarModal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Visita</button>
                    </div>
                </form>
            </div>

            <div id="tab-chat" class="tab-pane" style="display: none;">
                <div class="chat-container">
                    <div id="chatMessages" class="chat-messages-area">
                        <div class="text-center text-muted p-3">Cargando comentarios...</div>
                    </div>
                    <div class="chat-input-area">
                        <input type="text" id="chatInput" placeholder="Escribe un comentario..." disabled>
                        <button id="btnEnviarComentario" class="btn btn-primary" disabled><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos adicionales para badges de colores */
    .badge-success { background: #e6ffe6; color: #27ae60; } /* Bueno */
    .badge-warning { background: #fff8e9; color: #f39c12; } /* Regular */
    .badge-orange { background: #fff3cd; color: #d35400; }  /* Malo */
    .badge-danger { background: #ffe6e6; color: #e74c3c; }  /* Critico */
    .badge-secondary { background: #f0f0f0; color: #7f8c8d; }

    .modal-tabs { display: flex; border-bottom: 1px solid #eee; padding: 0 20px; background: #f9f9f9; }
    .tab-btn { padding: 15px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: #7f8c8d; border-bottom: 3px solid transparent; }
    .tab-btn.active { color: var(--primary-color); border-bottom-color: var(--primary-color); }
    .chat-container { display: flex; flex-direction: column; height: 400px; }
    .chat-messages-area { flex: 1; overflow-y: auto; padding: 15px; background: #f8f9fa; border: 1px solid #eee; border-radius: 8px; margin-bottom: 15px; }
    .chat-message { margin-bottom: 15px; display: flex; flex-direction: column; }
    .chat-message.own { align-items: flex-end; }
    .chat-bubble { background: white; padding: 10px 15px; border-radius: 15px; border: 1px solid #e0e0e0; max-width: 80%; }
    .chat-message.own .chat-bubble { background: #eaf3ff; border-color: #d0e1f5; }
    .chat-meta { font-size: 0.75rem; color: #999; margin-top: 4px; }
    .chat-user { font-weight: bold; font-size: 0.8rem; color: #2c3e50; }
    .chat-input-area { display: flex; gap: 10px; }
    .chat-input-area input { flex: 1; padding: 12px; border-radius: 25px; border: 1px solid #ddd; outline: none; }
    .link-foto { color: #3498db; font-weight: 500; text-decoration: none; }
</style>
@endpush

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { preview.src = e.target.result; container.style.display = 'block'; }
        reader.readAsDataURL(input.files[0]);
    } else { container.style.display = 'none'; }
}

window.switchModalTab = function(tabName) {
    document.querySelectorAll('.tab-pane').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabName).style.display = 'block';
    event.target.classList.add('active');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalVisita');
    const btnNueva = document.getElementById('btnNuevaVisita');
    const form = document.getElementById('formVisita');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentVisitaId = null;

    btnNueva.addEventListener('click', function() {
        form.reset();
        form.action = "{{ route('visitas.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('modalTitulo').textContent = "Registrar Nueva Visita";
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.querySelector('.modal-tabs').style.display = 'none';
        document.getElementById('tab-detalles').style.display = 'block';
        document.getElementById('tab-chat').style.display = 'none';
        
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('fecha_visita').value = now.toISOString().slice(0,16);
        
        modal.style.display = 'block';
    });

    document.querySelectorAll('#closeModal, #btnCancelarModal').forEach(el => {
        el.addEventListener('click', () => modal.style.display = 'none');
    });

    document.querySelectorAll('.btn-ver').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            currentVisitaId = id;

            document.querySelector('.modal-tabs').style.display = 'flex';
            switchModalTab('detalles'); 
            document.querySelector('.tab-btn').classList.add('active'); // Reset visual

            fetch(`/dashboard/visitas/${id}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('adulto_id').value = data.adulto_id;
                    document.getElementById('voluntario_id').value = data.voluntario_id;
                    document.getElementById('fecha_visita').value = data.fecha_visita.slice(0,16);
                    document.getElementById('tipo_visita').value = data.tipo_visita;
                    
                    // Llenar selectores de estado
                    document.getElementById('estado_emocional').value = data.estado_emocional;
                    document.getElementById('estado_fisico').value = data.estado_fisico || 'Regular'; // Valor por defecto si viene null
                    
                    document.getElementById('emergencia').value = data.emergencia ? 1 : 0;
                    document.getElementById('observaciones').value = data.observaciones;
                    
                    const container = document.getElementById('imagePreviewContainer');
                    const preview = document.getElementById('imagePreview');
                    if(data.foto_url) {
                        preview.src = data.foto_url;
                        container.style.display = 'block';
                    } else { container.style.display = 'none'; }

                    form.action = `/dashboard/visitas/${id}`;
                    document.getElementById('formMethod').value = "PUT";
                    document.getElementById('modalTitulo').textContent = "Detalles y Chat";
                    
                    loadChatMessages(data.comentarios);
                    enableChatInput();

                    modal.style.display = 'block';
                });
        });
    });

    // --- Chat Logic ---
    const chatArea = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const btnEnviar = document.getElementById('btnEnviarComentario');

    function loadChatMessages(comentarios) {
        chatArea.innerHTML = '';
        if(!comentarios || comentarios.length === 0) {
            chatArea.innerHTML = '<div class="text-center text-muted mt-4">No hay comentarios aún.</div>';
            return;
        }
        comentarios.forEach(c => appendMessage(c));
        scrollToBottom();
    }

    function appendMessage(c) {
        const isOwn = c.user_id == {{ auth()->id() }};
        const html = `<div class="chat-message ${isOwn ? 'own' : ''}"><div class="chat-bubble"><span class="chat-user">${c.user.name}</span>${c.contenido}</div><span class="chat-meta">${new Date(c.created_at).toLocaleString()}</span></div>`;
        chatArea.insertAdjacentHTML('beforeend', html);
    }

    function scrollToBottom() { chatArea.scrollTop = chatArea.scrollHeight; }
    function enableChatInput() { chatInput.disabled = false; btnEnviar.disabled = false; }

    btnEnviar.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) { if(e.key === 'Enter') sendMessage(); });

    function sendMessage() {
        const msg = chatInput.value.trim();
        if(!msg || !currentVisitaId) return;
        
        fetch(`/dashboard/visitas/${currentVisitaId}/comentarios`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ contenido: msg })
        })
        .then(r => r.json())
        .then(newComment => {
            if(chatArea.querySelector('.text-center')) chatArea.innerHTML = '';
            appendMessage(newComment);
            chatInput.value = '';
            scrollToBottom();
        });
    }

    // Eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Borrar?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Sí'
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