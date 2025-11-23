@extends('layouts.dashboard')

@section('title', 'Auditoría de Actividades')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Historial de Actividades</h1>
            <p>Registro de seguridad de todas las acciones realizadas en el sistema.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Módulo</th>
                            <th>Descripción</th>
                            <th>Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:30px; height:30px; background:var(--primary-color); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:0.8rem;">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $log->user->name }}</strong><br>
                                            <small style="color:#999;">{{ $log->user->role }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $color = 'gray';
                                        if($log->accion == 'Crear') $color = 'green';
                                        if($log->accion == 'Actualizar') $color = 'orange';
                                        if($log->accion == 'Eliminar') $color = 'red';
                                    @endphp
                                    <span class="badge" style="background: {{ $color }}; color: white;">{{ $log->accion }}</span>
                                </td>
                                <td>{{ $log->modulo }}</td>
                                <td>{{ $log->descripcion }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No hay actividad registrada aún.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-container">{{ $logs->links() }}</div>
        </div>
    </div>
</div>
@endsection