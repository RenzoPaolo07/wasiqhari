@extends('layouts.dashboard')

@section('title', $title ?? 'Gestión de Voluntarios')

@section('content')
<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Gestión de Voluntarios</h1>
            <p>Administra a los miembros de tu equipo.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('register') }}" class="btn btn-primary-outline" target="_blank">
                <i class="fas fa-user-plus"></i> Invitar Voluntario
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error_form_edit'))
        <div class="alert alert-danger">
            <strong>¡Error!</strong> {{ session('error_form_edit') }}
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            <h3>Voluntarios Registrados</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Distrito</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($voluntarios as $vol) <tr id="fila-voluntario-{{ $vol->id }}">
                                <td>{{ $vol->user->name ?? 'Usuario no encontrado' }}</td>
                                <td>{{ $vol->user->email ?? 'N/A' }}</td>
                                <td>{{ $vol->telefono ?? 'N/A' }}</td>
                                <td>{{ $vol->distrito ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-estado-{{ strtolower($vol->estado) }}">
                                        {{ $vol->estado }}
                                    </span>
                                </td>
                                <td>
                                    @if($vol->telefono)
                                        <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $vol->telefono) }}?text=Hola {{ $vol->user->name }}, te escribo desde la coordinación de WasiQhari." 
                                           target="_blank" 
                                           class="btn-action btn-whatsapp" 
                                           title="Chat en WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('voluntarios.credencial', $vol->id) }}" target="_blank" class="btn-action btn-credencial" title="Descargar Credencial">
                                        <i class="fas fa-id-card"></i>
                                    </a>
                                    
                                    <button class="btn-action btn-ver" 
                                            data-id="{{ $vol->id }}" 
                                            title="Ver / Editar">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button class="btn-action btn-eliminar" 
                                            data-id="{{ $vol->id }}" 
                                            data-name="{{ $vol->user->name ?? 'Voluntario' }}" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay voluntarios registrados todavía.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-container">
                {{ $voluntarios->links() }}
            </div>
        </div>
    </div>

</div> 

<div id="modalVoluntario" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3 id="modalTitulo">Editar Voluntario</h3>
            <span class="close" id="closeModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="formVoluntario" action="" method="POST">
                @csrf
                @method('PUT') 
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nombre Completo *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" maxlength="15">
                    </div>
                    
                    <div class="form-group">
                        <label for="distrito">Distrito</label>
                        <select id="distrito" name="distrito">
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
                        <label for="disponibilidad">Disponibilidad *</label>
                        <select id="disponibilidad" name="disponibilidad" required>
                            <option value="Flexible">Flexible</option>
                            <option value="Mañanas">Mañanas</option>
                            <option value="Tardes">Tardes</option>
                            <option value="Noches">Noches</option>
                            <option value="Fines de semana">Fines de semana</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado *</label>
                        <select id="estado" name="estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Suspendido">Suspendido</option>
                        </select>
                    </div>
                    
                    <div class="form-group" data-span="3">
                        <label for="zona_cobertura">Zonas de Cobertura (separadas por coma)</label>
                        <input type="text" id="zona_cobertura" name="zona_cobertura" placeholder="Ej: Wanchaq, Magisterio, Marcavalle">
                    </div>
                    
                    <div class="form-group" data-span="3">
                        <label for="habilidades">Habilidades (separadas por coma)</label>
                        <textarea id="habilidades" name="habilidades" rows="3" placeholder="Ej: Primeros auxilios, escucha activa, cocina"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="btnCancelarModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('styles')
<style>
.badge-estado-activo { background: #e6ffe6; color: #27ae60; }
.badge-estado-inactivo { background: #f8f9fa; color: #7f8c8d; border: 1px solid #e0e0e0; }
.badge-estado-suspendido { background: #ffe6e6; color: #e74c3c; }

.btn-primary-outline {
    background: #fff;
    color: var(--primary-color, #e74c3c);
    border: 2px solid var(--primary-color, #e74c3c);
    padding: 10px 20px;
    font-size: 0.95rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-primary-outline:hover {
    background: var(--primary-color, #e74c3c);
    color: #fff;
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const modal = document.getElementById('modalVoluntario');
    const closeModal = document.getElementById('closeModal');
    const btnCancelarModal = document.getElementById('btnCancelarModal');
    const modalTitulo = document.getElementById('modalTitulo');
    const formVoluntario = document.getElementById('formVoluntario');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function cerrarModal() {
        if(modal) {
            modal.style.display = 'none';
            formVoluntario.reset();
        }
    }
    if(closeModal) closeModal.addEventListener('click', cerrarModal);
    if(btnCancelarModal) btnCancelarModal.addEventListener('click', cerrarModal);
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            cerrarModal();
        }
    });

    document.querySelectorAll('.btn-ver').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            fetch(`/dashboard/voluntarios/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    formVoluntario.reset();
                    
                    document.getElementById('name').value = data.user.name;
                    document.getElementById('email').value = data.user.email;
                    document.getElementById('telefono').value = data.telefono;
                    document.getElementById('distrito').value = data.distrito;
                    document.getElementById('disponibilidad').value = data.disponibilidad;
                    document.getElementById('estado').value = data.estado;
                    document.getElementById('zona_cobertura').value = data.zona_cobertura;
                    document.getElementById('habilidades').value = data.habilidades;

                    formVoluntario.action = `/dashboard/voluntarios/${id}`; 
                    modalTitulo.textContent = "Editar Voluntario";
                    
                    modal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
                });
        });
    });

    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            Swal.fire({
                title: `¿Estás seguro?`,
                text: `¡Se eliminará a ${name}! Esto también eliminará su cuenta de usuario.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Sí, ¡bórralo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dashboard/voluntarios/${id}`, {
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
                            Swal.fire('¡Eliminado!', data.message, 'success');
                            document.getElementById(`fila-voluntario-${id}`).remove();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo eliminar.', 'error');
                    });
                }
            });
        });
    });
});
</script>
@endpush