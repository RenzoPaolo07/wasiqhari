@extends('layouts.dashboard')

@section('title', $title)

@section('content')
<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-content">
            <a href="{{ route('adultos') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
            <h1>Expediente Evolutivo</h1>
            <p>{{ $adulto->nombres }} {{ $adulto->apellidos }} ({{ $adulto->edad }} años)</p>
        </div>
        <div class="header-actions">
            <div class="health-status">
                <span class="badge badge-{{ strtolower($adulto->estado_salud) }}">
                    Salud: {{ $adulto->estado_salud }}
                </span>
                <span class="badge badge-riesgo-{{ strtolower($adulto->nivel_riesgo) }}">
                    Riesgo: {{ $adulto->nivel_riesgo }}
                </span>
            </div>
        </div>
    </div>

    <div class="content-grid" style="grid-template-columns: 2fr 1fr;">
        
        <div class="left-col" style="display: flex; flex-direction: column; gap: 20px;">
            
            <div class="content-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-area"></i> Evolución de Bienestar</h3>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" height="300"></canvas>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3><i class="fas fa-history"></i> Historial de Visitas</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($visitas as $visita)
                            <div class="timeline-item {{ $visita->emergencia ? 'emergency' : '' }}">
                                <div class="timeline-date">
                                    <span>{{ $visita->fecha_visita->format('d M') }}</span>
                                    <small>{{ $visita->fecha_visita->format('Y') }}</small>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="tag">{{ $visita->tipo_visita }}</span>
                                        <span class="time"><i class="fas fa-clock"></i> {{ $visita->fecha_visita->format('H:i A') }}</span>
                                    </div>
                                    
                                    <p class="timeline-desc">
                                        <strong>Voluntario:</strong> {{ $visita->voluntario?->user?->name ?? 'Voluntario no encontrado' }}<br>
                                        {{ $visita->observaciones }}
                                    </p>
                                    
                                    <div class="timeline-stats">
                                        <span class="mini-stat"><i class="fas fa-heart"></i> Físico: {{ $visita->estado_fisico }}</span>
                                        <span class="mini-stat"><i class="fas fa-smile"></i> Ánimo: {{ $visita->estado_emocional }}</span>
                                    </div>

                                    @if($visita->foto_evidencia)
                                        <div class="timeline-photo">
                                            <img src="{{ asset('storage/' . $visita->foto_evidencia) }}" alt="Evidencia" onclick="window.open(this.src)">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">No hay historial de visitas registrado.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="right-col">
            <div class="content-card">
                <div class="card-header">
                    <h3>Datos del Beneficiario</h3>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <label>DNI</label>
                            <p>{{ $adulto->dni ?? 'No registrado' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Distrito</label>
                            <p>{{ $adulto->distrito }}</p>
                        </div>
                        <div class="info-item">
                            <label>Dirección</label>
                            <p>{{ $adulto->direccion }}</p>
                        </div>
                        <div class="info-item">
                            <label>Necesidades</label>
                            <p class="text-danger">{{ $adulto->necesidades ?? 'Ninguna especificada' }}</p>
                        </div>
                         <div class="info-item">
                            <label>Actividad</label>
                            <p>{{ $adulto->actividad_calle }}</p>
                        </div>
                    </div>
                    <div style="margin-top: 20px; text-align: center;">
                         <a href="https://wa.me/?text=Ficha de {{ $adulto->nombres }}: {{ route('adultos.evolucion', $adulto->id) }}" target="_blank" class="btn btn-secondary" style="width: 100%;">
                            <i class="fab fa-whatsapp"></i> Compartir Ficha
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-back { text-decoration: none; color: #7f8c8d; margin-right: 10px; display: block; margin-bottom: 5px; font-size: 0.9rem; }
    .btn-back:hover { color: var(--primary-color); }
    
    /* Timeline Styles */
    .timeline { position: relative; padding-left: 20px; border-left: 2px solid #e0e0e0; margin-left: 10px; }
    .timeline-item { position: relative; margin-bottom: 30px; padding-left: 20px; }
    .timeline-item::before { content: ''; position: absolute; left: -26px; top: 0; width: 10px; height: 10px; border-radius: 50%; background: white; border: 2px solid var(--primary-color); }
    .timeline-item.emergency::before { background: #e74c3c; border-color: #e74c3c; }
    
    .timeline-date { margin-bottom: 5px; color: #7f8c8d; font-weight: bold; font-size: 0.9rem; }
    .timeline-content { background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee; }
    .timeline-item.emergency .timeline-content { border-left: 3px solid #e74c3c; background: #fff5f5; }
    
    .timeline-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
    .tag { background: #e0e0e0; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; color: #555; }
    .time { font-size: 0.8rem; color: #999; }
    
    .timeline-desc { font-size: 0.9rem; color: #2c3e50; margin-bottom: 10px; line-height: 1.4; }
    
    .timeline-stats { display: flex; gap: 10px; font-size: 0.8rem; color: #7f8c8d; border-top: 1px solid #eee; padding-top: 8px; }
    .mini-stat i { color: var(--primary-color); margin-right: 4px; }
    
    .timeline-photo img { width: 100px; height: 60px; object-fit: cover; border-radius: 5px; margin-top: 10px; cursor: pointer; transition: transform 0.2s; }
    .timeline-photo img:hover { transform: scale(1.1); }

    /* Info List */
    .info-item { margin-bottom: 15px; border-bottom: 1px solid #f5f5f5; padding-bottom: 10px; }
    .info-item label { display: block; font-size: 0.8rem; color: #95a5a6; margin-bottom: 2px; text-transform: uppercase; }
    .info-item p { margin: 0; font-weight: 500; color: #2c3e50; }
    .text-danger { color: #e74c3c !important; }
    
    @media (max-width: 768px) {
        .content-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evolutionChart');
    if (ctx) {
        // Datos desde el controlador
        const labels = @json($chartLabels);
        const fisicoData = @json($chartFisico);
        const emocionalData = @json($chartEmocional);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Estado Físico',
                        data: fisicoData,
                        borderColor: '#27ae60', // Verde
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Estado Emocional',
                        data: emocionalData,
                        borderColor: '#f39c12', // Naranja
                        backgroundColor: 'rgba(243, 156, 18, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let val = context.raw;
                                let text = '';
                                if(val >= 80) text = 'Muy Bien';
                                else if(val >= 60) text = 'Regular';
                                else if(val >= 40) text = 'Malo';
                                else text = 'Crítico';
                                return context.dataset.label + ': ' + text + ' (' + val + ')';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { borderDash: [2, 4] },
                        ticks: {
                            callback: function(value) {
                                if(value == 100) return 'Excelente';
                                if(value == 50) return 'Regular';
                                if(value == 0) return 'Crítico';
                                return '';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush