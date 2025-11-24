@extends('layouts.dashboard')

@section('title', 'Gestión de Inventario')

@section('content')
<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Gestión de Inventario</h1>
            <p>Control de donaciones, alimentos y medicinas.</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="btnNuevoItem">
                <i class="fas fa-box-open"></i> Registrar Entrada
            </button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalItems }}</h3>
                <p>Productos Registrados</p>
            </div>
        </div>
        <div class="stat-card" style="border-left: 4px solid {{ $stockBajo > 0 ? '#e74c3c' : '#27ae60' }};">
            <div class="stat-icon {{ $stockBajo > 0 ? 'danger' : 'success' }}">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stockBajo }}</h3>
                <p>Alertas de Stock Bajo</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error_form') || session('error_form_edit'))
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Revisa el formulario.
             @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </div>
    @endif

    <div class="content-card">
        <div class="card-header search-header">
            <h3>Inventario Actual</h3>
            <div class="search-container">
                <form action="{{ route('inventario') }}" method="GET" style="display:flex; gap:10px;">
                    <select name="categoria" class="search-input" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">Todas las Categorías</option>
                        <option value="Alimentos" {{ request('categoria') == 'Alimentos' ? 'selected' : '' }}>Alimentos</option>
                        <option value="Medicinas" {{ request('categoria') == 'Medicinas' ? 'selected' : '' }}>Medicinas</option>
                        <option value="Ropa" {{ request('categoria') == 'Ropa' ? 'selected' : '' }}>Ropa</option>
                        <option value="Equipamiento" {{ request('categoria') == 'Equipamiento' ? 'selected' : '' }}>Equipamiento</option>
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar producto..." class="search-input">
                    <button type="submit" class="btn btn-secondary" style="padding: 8px 15px;"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr id="fila-item-{{ $item->id }}">
                                <td>
                                    <strong>{{ $item->nombre }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($item->descripcion, 30) }}</small>
                                </td>
                                <td><span class="badge badge-cat">{{ $item->categoria }}</span></td>
                                <td>
                                    <strong style="font-size: 1.1rem;">{{ $item->cantidad }}</strong> 
                                    <span class="text-muted">{{ $item->unidad }}</span>
                                </td>
                                <td>
                                    @if($item->fecha_vencimiento)
                                        {{ $item->fecha_vencimiento->format('d/m/Y') }}
                                        @if($item->fecha_vencimiento < now()->addMonth())
                                            <i class="fas fa-exclamation-triangle text-danger" title="Por vencer"></i>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->cantidad == 0)
                                        <span class="badge badge-agotado">Agotado</span>
                                    @elseif($item->cantidad < 10)
                                        <span class="badge badge-bajo">Bajo Stock</span>
                                    @else
                                        <span class="badge badge-disponible">Disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn-action btn-ver" data-id="{{ $item->id }}" title="Editar Stock">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action btn-eliminar" data-id="{{ $item->id }}" data-name="{{ $item->nombre }}" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No hay productos en el inventario.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-container">{{ $items->links() }}</div>
        </div>
    </div>
</div>

<div id="modalItem" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitulo">Registrar Producto</h3>
            <span class="close" id="closeModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="formItem" action="{{ route('inventario.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Nombre del Producto *</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Categoría *</label>
                        <select id="categoria" name="categoria" required>
                            <option value="Alimentos">Alimentos</option>
                            <option value="Medicinas">Medicinas</option>
                            <option value="Ropa">Ropa</option>
                            <option value="Equipamiento">Equipamiento</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Unidad de Medida *</label>
                        <input type="text" id="unidad" name="unidad" placeholder="Ej: Kg, Latas, Cajas" required>
                    </div>

                    <div class="form-group">
                        <label>Cantidad Inicial *</label>
                        <input type="number" id="cantidad" name="cantidad" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Fecha Vencimiento</label>
                        <input type="date" id="fecha_vencimiento" name="fecha_vencimiento">
                    </div>
                    
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Descripción / Notas</label>
                        <textarea id="descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="btnCancelarModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge-cat { background: #eaf3ff; color: #3498db; border: 1px solid #d0e1f5; }
    .badge-agotado { background: #ffe6e6; color: #e74c3c; }
    .badge-bajo { background: #fff8e9; color: #f39c12; }
    .badge-disponible { background: #e6ffe6; color: #27ae60; }
    .text-danger { color: #e74c3c; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalItem');
    const form = document.getElementById('formItem');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Nuevo Item
    document.getElementById('btnNuevoItem').addEventListener('click', () => {
        form.reset();
        form.action = "{{ route('inventario.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('modalTitulo').textContent = "Registrar Producto";
        modal.style.display = 'block';
    });

    // Cerrar
    document.querySelectorAll('#closeModal, #btnCancelarModal').forEach(el => {
        el.addEventListener('click', () => modal.style.display = 'none');
    });

    // Editar
    document.querySelectorAll('.btn-ver').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            fetch(`/dashboard/inventario/${id}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('categoria').value = data.categoria;
                    document.getElementById('unidad').value = data.unidad;
                    document.getElementById('cantidad').value = data.cantidad;
                    if(data.fecha_vencimiento) document.getElementById('fecha_vencimiento').value = data.fecha_vencimiento.split('T')[0];
                    document.getElementById('descripcion').value = data.descripcion;

                    form.action = `/dashboard/inventario/${id}`;
                    document.getElementById('formMethod').value = "PUT";
                    document.getElementById('modalTitulo').textContent = "Editar Stock / Producto";
                    modal.style.display = 'block';
                });
        });
    });

    // Eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Eliminar producto?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dashboard/inventario/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
                    }).then(r => r.json()).then(data => {
                        if(data.success) {
                            document.getElementById(`fila-item-${id}`).remove();
                            Swal.fire('Eliminado', '', 'success');
                        }
                    });
                }
            });
        });
    });
});
</script>
@endpush