@extends('layouts.dashboard')

@section('title', $title ?? 'Dashboard - WasiQhari')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Dashboard Principal</h1>
            <p>Bienvenido, {{ Auth::user()->name }}</p>
        </div>
        <div class="header-actions">
            </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_adultos'] ?? 0 }}</h3>
                <p>Adultos Mayores</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-hands-helping"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_voluntarios'] ?? 0 }}</h3>
                <p>Voluntarios</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_visitas'] ?? 0 }}</h3>
                <p>Visitas Realizadas</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['adultos_criticos'] ?? 0 }}</h3>
                <p>Casos Críticos</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="content-grid">
            <div class="content-card">
                <div class="card-header">
                    <h3>Distribución por Distritos</h3>
                </div>
                <div class="card-body">
                    <div class="mapa-distribucion">
                        <div id="map" style="height: 350px; border-radius: 10px; width: 100%; z-index: 1;"></div>
                    </div>
                    <div class="distrito-stats">
                        @forelse($stats['distribucion_distritos'] as $distrito)
                        <div class="distrito-item">
                            <span class="distrito-name">{{ $distrito->distrito ?? 'N/A' }}</span>
                            <span class="distrito-count">{{ $distrito->cantidad ?? 0 }} personas</span>
                        </div>
                        @empty
                        <div class="distrito-item">
                            <span class="distrito-name">Sin datos</span>
                            <span class="distrito-count">0</span>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3>Últimas Visitas</h3>
                    <a href="{{ route('visitas') }}" class="btn-link">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="visitas-list">
                        @forelse($stats['ultimas_visitas'] as $visita)
                            <div class="visita-item">
                                <div class="visita-info">
                                    <h4>{{ $visita->adultoMayor->nombres ?? 'N/A' }} {{ $visita->adultoMayor->apellidos ?? '' }}</h4>
                                    <p>Por: {{ $visita->voluntario->user->name ?? 'N/A' }}</p>
                                    <small>{{ $visita->fecha_visita->diffForHumans() }}</small>
                                </div>
                                <div class="visita-status {{ $visita->emergencia ? 'emergency' : 'normal' }}">
                                    {{ $visita->emergencia ? '🚨 Emergencia' : '✅ Normal' }}
                                </div>
                            </div>
                        @empty
                            <p class="no-data">No hay visitas registradas aún</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <div class="card-header">
                    <h3>Estado de Salud</h3>
                </div>
                <div class="card-body">
                    <canvas id="saludChart" width="400" height="200"></canvas>
                </div>
            </div>
            
            <div class="content-card">
                <div class="card-header">
                    <h3>Actividades en Calle</h3>
                </div>
                <div class="card-body">
                    <canvas id="actividadesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. INICIALIZAR MAPA
    if (document.getElementById('map')) {
        // Coordenadas de Cusco
        var map = L.map('map').setView([-13.5319, -71.9675], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Datos pasados desde el controlador
        const adultosData = @json($adultosParaMapa ?? []);

        // Iconos personalizados
        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });
        
        var blueIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });

        // Poner marcadores
        adultosData.forEach(adulto => {
            if(adulto.lat && adulto.lon) {
                L.marker([adulto.lat, adulto.lon], {
                    icon: adulto.nivel_riesgo === 'Alto' ? redIcon : blueIcon
                })
                .addTo(map)
                .bindPopup(`<b>${adulto.nombres} ${adulto.apellidos}</b><br>Riesgo: ${adulto.nivel_riesgo}`);
            }
        });
    }

    // 2. GRÁFICOS
    // Salud
    const saludCtx = document.getElementById('saludChart');
    if (saludCtx) {
        const saludData = @json($saludData ?? []);
        new Chart(saludCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(saludData),
                datasets: [{
                    data: Object.values(saludData),
                    backgroundColor: ['#27ae60', '#f39c12', '#e74c3c', '#c0392b'],
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'right' } } }
        });
    }

    // Actividades
    const actCtx = document.getElementById('actividadesChart');
    if (actCtx) {
        const actData = @json($actividadesData ?? []);
        new Chart(actCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(actData),
                datasets: [{
                    label: 'Cantidad',
                    data: Object.values(actData),
                    backgroundColor: '#3498db',
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* Variables CSS específicas para el dashboard */
.dashboard-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 2px solid #f0f0f0;
}

.header-content h1 {
    color: var(--dark-color, #2c3e50);
    margin: 0 0 5px 0;
    font-size: 2.2rem;
    font-weight: 700;
}

.header-content p {
    color: var(--text-light, #7f8c8d);
    margin: 0;
    font-size: 1.1rem;
}

.header-actions .btn {
    padding: 12px 24px;
    font-size: 1rem;
    white-space: nowrap;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    flex-shrink: 0;
}

.stat-icon.primary { 
    background: linear-gradient(135deg, #e74c3c, #c0392b); 
}
.stat-icon.success { 
    background: linear-gradient(135deg, #27ae60, #20c997); 
}
.stat-icon.warning { 
    background: linear-gradient(135deg, #f39c12, #fd7e14); 
}
.stat-icon.danger { 
    background: linear-gradient(135deg, #e74c3c, #c0392b); 
}

.stat-info h3 {
    font-size: 2.2rem;
    margin: 0;
    color: var(--dark-color, #2c3e50);
    font-weight: 700;
}

.stat-info p {
    margin: 5px 0 0 0;
    color: var(--text-light, #7f8c8d);
    font-size: 0.9rem;
}

.dashboard-content {
    margin-top: 30px;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

.content-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    border: 1px solid #e0e0e0;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
}

.card-header h3 {
    margin: 0;
    color: var(--dark-color, #2c3e50);
    font-size: 1.3rem;
    font-weight: 600;
}

.card-body {
    padding: 25px;
}

.map-placeholder {
    height: 300px;
    border-radius: 10px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    flex-direction: column;
    gap: 10px;
}

.map-placeholder i {
    font-size: 3rem;
    opacity: 0.7;
}

.map-placeholder p {
    margin: 0;
    font-size: 1rem;
}

.distrito-stats {
    margin-top: 20px;
}

.distrito-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f5f5f5;
    transition: all 0.3s ease;
}

.distrito-item:hover {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px 15px;
}

.distrito-item:last-child {
    border-bottom: none;
}

.distrito-name {
    font-weight: 500;
    color: var(--dark-color, #2c3e50);
}

.distrito-count {
    color: var(--text-light, #7f8c8d);
    font-size: 0.9rem;
}

.visitas-list {
    max-height: 400px;
    overflow-y: auto;
}

.visita-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f5f5f5;
    transition: all 0.3s ease;
}

.visita-item:hover {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin: 0 -15px;
}

.visita-item:last-child {
    border-bottom: none;
}

.visita-info h4 {
    margin: 0 0 5px 0;
    color: var(--dark-color, #2c3e50);
    font-size: 1rem;
    font-weight: 600;
}

.visita-info p {
    margin: 0 0 5px 0;
    color: var(--text-light, #7f8c8d);
    font-size: 0.85rem;
}

.visita-info small {
    color: var(--text-light, #7f8c8d);
    font-size: 0.8rem;
}

.visita-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.visita-status.emergency {
    background: #ffe6e6;
    color: #e74c3c;
    border: 1px solid #ffcccc;
}

.visita-status.normal {
    background: #e6ffe6;
    color: #27ae60;
    border: 1px solid #ccffcc;
}

.no-data {
    text-align: center;
    color: var(--text-light, #7f8c8d);
    font-style: italic;
    padding: 40px 0;
    margin: 0;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 15px;
    width: 90%;
    max-width: 600px;
    animation: modalSlideIn 0.3s ease;
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
}

@keyframes modalSlideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.modal-header h3 {
    margin: 0;
    color: white;
    font-size: 1.4rem;
    font-weight: 600;
}

.close {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    line-height: 1;
}

.close:hover {
    color: #f0f0f0;
    transform: scale(1.1);
}

.modal-body {
    padding: 25px;
}

.ayuda-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.ayuda-option {
    background: #f8f9fa;
    padding: 25px 20px;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.ayuda-option:hover {
    border-color: #e74c3c;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.2);
}

.ayuda-option i {
    font-size: 2.5rem;
    color: #e74c3c;
    margin-bottom: 15px;
}

.ayuda-option h4 {
    margin: 0 0 10px 0;
    color: var(--dark-color, #2c3e50);
    font-size: 1.1rem;
    font-weight: 600;
}

.ayuda-option p {
    margin: 0;
    color: var(--text-light, #7f8c8d);
    font-size: 0.9rem;
    line-height: 1.4;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--dark-color, #2c3e50);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #e74c3c;
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
}

.form-group input[readonly] {
    background: #f8f9fa;
    cursor: not-allowed;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
    font-family: inherit;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 25px;
}

.btn-link {
    color: #e74c3c;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    background: none;
    border: none;
    cursor: pointer;
}

.btn-link:hover {
    text-decoration: underline;
    color: #c0392b;
}

/* Responsive */
@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn {
        width: 100%;
    }
    
    .ayuda-options {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .header-content h1 {
        font-size: 1.8rem;
    }
    
    .stat-card {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .dashboard-container {
        padding: 15px;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .visita-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .visita-status {
        align-self: flex-start;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Gráficos
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Estado de Salud
    const saludCtx = document.getElementById('saludChart').getContext('2d');
    const saludChart = new Chart(saludCtx, {
        type: 'doughnut',
        data: {
            labels: ['Crítico', 'Malo', 'Regular', 'Bueno'],
            datasets: [{
                data: [35, 30, 20, 15],
                backgroundColor: ['#e74c3c', '#f39c12', '#3498db', '#27ae60'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Gráfico de Actividades
    const actividadesCtx = document.getElementById('actividadesChart').getContext('2d');
    const actividadesChart = new Chart(actividadesCtx, {
        type: 'bar',
        data: {
            labels: ['Pide limosna', 'Vende dulces', 'Vende artesanías', 'Recicla', 'Otros'],
            datasets: [{
                label: 'Cantidad de Personas',
                data: [40, 25, 15, 12, 8],
                backgroundColor: [
                    'rgba(231, 76, 60, 0.8)',
                    'rgba(243, 156, 18, 0.8)',
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(39, 174, 96, 0.8)',
                    'rgba(155, 89, 182, 0.8)'
                ],
                borderColor: [
                    '#e74c3c',
                    '#f39c12',
                    '#3498db',
                    '#27ae60',
                    '#9b59b6'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

// Modal de Ayuda
const modal = document.getElementById('ayudaModal');
const span = document.getElementsByClassName('close')[0];

function iniciarAyuda() {
    modal.style.display = 'block';
    document.getElementById('formularioAyuda').style.display = 'none';
    document.querySelector('.ayuda-options').style.display = 'grid';
}

if (span) {
    span.onclick = function() {
        modal.style.display = 'none';
        resetFormularioAyuda();
    }
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
        resetFormularioAyuda();
    }
}

function seleccionarAyuda(tipo) {
    const tipos = {
        'visita': 'Realizar Visita',
        'alimentos': 'Entrega de Alimentos',
        'medicina': 'Apoyo Médico',
        'otro': 'Otro Tipo de Ayuda'
    };
    
    document.getElementById('tipo_ayuda').value = tipos[tipo];
    document.querySelector('.ayuda-options').style.display = 'none';
    document.getElementById('formularioAyuda').style.display = 'block';
}

function cancelarAyuda() {
    document.getElementById('formularioAyuda').style.display = 'none';
    document.querySelector('.ayuda-options').style.display = 'grid';
    resetFormularioAyuda();
}

function resetFormularioAyuda() {
    document.getElementById('ayudaForm').reset();
    document.getElementById('tipo_ayuda').value = '';
}

const ayudaForm = document.getElementById('ayudaForm');
if (ayudaForm) {
    ayudaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            tipo: document.getElementById('tipo_ayuda').value,
            disponibilidad: document.getElementById('disponibilidad').value,
            zona: document.getElementById('zona_ayuda').value,
            mensaje: document.getElementById('mensaje_ayuda').value,
            _token: document.querySelector('input[name="_token"]').value
        };
        
        // Simular envío de solicitud
        Swal.fire({
            title: '¡Solicitud Enviada!',
            text: 'Hemos recibido tu solicitud de ayuda. Te contactaremos pronto para coordinar.',
            icon: 'success',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#e74c3c'
        });
        
        modal.style.display = 'none';
        resetFormularioAyuda();
    });
}

// Efectos de hover para tarjetas
document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// Smooth scroll para las listas
document.querySelectorAll('.visitas-list').forEach(list => {
    list.addEventListener('wheel', function(e) {
        if (this.scrollHeight > this.clientHeight) {
            e.preventDefault();
            this.scrollTop += e.deltaY;
        }
    });
});
</script>
@endpush