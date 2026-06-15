@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">🚨 Monitoreo IoT - Wasiqhari</h1>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Dispositivos Activos</h5>
                    <h2 class="card-text" id="dispositivos-activos">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Alertas Hoy</h5>
                    <h2 class="card-text" id="alertas-hoy">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Pacientes en Riesgo</h5>
                    <h2 class="card-text" id="pacientes-riesgo">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Adultos Monitoreados</h5>
                    <h2 class="card-text" id="total-pacientes">0</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa de Ubicaciones -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt"></i> Ubicación de Adultos Mayores
                </div>
                <div class="card-body">
                    <div id="mapa" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas en Tiempo Real -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-bell"></i> 🚨 Últimas Alertas IoT
                    <span class="badge bg-light text-dark float-end" id="alertas-count">0</span>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <div id="alertas-container">
                        <div class="text-center">Cargando alertas...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-line"></i> Estadísticas de Alertas
                </div>
                <div class="card-body">
                    <canvas id="graficoAlertas" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Adultos Mayores con Dispositivos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-users"></i> Adultos Mayores con Dispositivos IoT
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tabla-pacientes">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>Dispositivo</th>
                                    <th>Última Lectura</th>
                                    <th>Estado</th>
                                    <th>Riesgo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="pacientes-tbody">
                                <tr><td colspan="8" class="text-center">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Variables globales
let graficoAlertas = null;
let audio = new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3');

// Función para reproducir sonido de alerta
function reproducirAlerta() {
    audio.play().catch(e => console.log('Audio no soportado'));
}

// Cargar resumen de estadísticas
async function cargarResumen() {
    try {
        const response = await fetch('/api/iot/resumen');
        const data = await response.json();
        
        document.getElementById('dispositivos-activos').textContent = data.dispositivos_activos || 0;
        document.getElementById('alertas-hoy').textContent = data.alertas_hoy || 0;
        document.getElementById('pacientes-riesgo').textContent = data.pacientes_riesgo || 0;
        document.getElementById('total-pacientes').textContent = data.total_pacientes || 0;
    } catch (error) {
        console.error('Error cargando resumen:', error);
    }
}

// Cargar alertas en tiempo real
async function cargarAlertas() {
    try {
        const response = await fetch('/api/iot/alertas-recientes');
        const data = await response.json();
        
        const container = document.getElementById('alertas-container');
        const alertasCount = document.getElementById('alertas-count');
        
        alertasCount.textContent = data.length;
        
        if (data.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No hay alertas recientes</div>';
            return;
        }
        
        let html = '<div class="list-group">';
        data.forEach(alerta => {
            const fecha = new Date(alerta.timestamp);
            const hora = fecha.toLocaleTimeString();
            const fechaStr = fecha.toLocaleDateString();
            
            let clase = 'list-group-item-danger';
            if (alerta.tipo_alerta === 'caida') clase = 'list-group-item-warning';
            if (alerta.tipo_alerta === 'panico_manual') clase = 'list-group-item-danger';
            
            html += `
                <div class="list-group-item ${clase} alerta-item" data-id="${alerta.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>🚨 ${alerta.tipo_alerta.toUpperCase()}</strong>
                        <small class="text-muted">${fechaStr} - ${hora}</small>
                    </div>
                    <div class="mt-2">
                        <div><i class="fas fa-user"></i> <strong>Paciente:</strong> ${alerta.paciente}</div>
                        <div><i class="fas fa-tachometer-alt"></i> <strong>Fuerza G:</strong> ${alerta.fuerza_g} G</div>
                        <div><i class="fas fa-microchip"></i> <strong>Dispositivo:</strong> ${alerta.dispositivo_id || 'ESP32'}</div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
        
        // Reproducir sonido si hay alertas nuevas
        if (data.length > 0 && data[0].es_nueva) {
            reproducirAlerta();
            mostrarNotificacion(data[0]);
        }
    } catch (error) {
        console.error('Error cargando alertas:', error);
        document.getElementById('alertas-container').innerHTML = '<div class="alert alert-warning">Error cargando alertas</div>';
    }
}

// Mostrar notificación emergente
function mostrarNotificacion(alerta) {
    if (Notification.permission === 'granted') {
        new Notification('🚨 Alerta IoT Wasiqhari', {
            body: `${alerta.paciente} - ${alerta.tipo_alerta} (${alerta.fuerza_g} G)`,
            icon: '/favicon.ico'
        });
    }
    
    // SweetAlert emergente
    Swal.fire({
        title: '🚨 ¡ALERTA IOT!',
        html: `<strong>Paciente:</strong> ${alerta.paciente}<br>
               <strong>Tipo:</strong> ${alerta.tipo_alerta}<br>
               <strong>Fuerza G:</strong> ${alerta.fuerza_g} G<br>
               <strong>Hora:</strong> ${new Date(alerta.timestamp).toLocaleTimeString()}`,
        icon: 'error',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true
    });
}

// Cargar lista de pacientes
async function cargarPacientes() {
    try {
        const response = await fetch('/api/iot/pacientes');
        const data = await response.json();
        
        const tbody = document.getElementById('pacientes-tbody');
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center">No hay pacientes registrados</td></tr>';
            return;
        }
        
        tbody.innerHTML = '';
        data.forEach(paciente => {
            const ultimoContacto = paciente.ultimo_contacto_iot ? new Date(paciente.ultimo_contacto_iot).toLocaleString() : 'Nunca';
            const estadoClass = paciente.alertas_activas ? 'bg-success' : 'bg-secondary';
            const estadoText = paciente.alertas_activas ? 'Activo' : 'Inactivo';
            const riesgoClass = paciente.nivel_riesgo === 'Alto' ? 'badge bg-danger' : 
                               (paciente.nivel_riesgo === 'Medio' ? 'badge bg-warning' : 'badge bg-success');
            
            tbody.innerHTML += `
                <tr>
                    <td>${paciente.id}</td>
                    <td>${paciente.nombres} ${paciente.apellidos}</td>
                    <td>${paciente.dni}</td>
                    <td><code>${paciente.dispositivo_id || 'No asignado'}</code></td>
                    <td><small>${ultimoContacto}</small></td>
                    <td><span class="badge ${estadoClass}">${estadoText}</span></td>
                    <td><span class="${riesgoClass}">${paciente.nivel_riesgo || 'No definido'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="verDetalles(${paciente.id})">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                    </td>
                </tr>
            `;
        });
    } catch (error) {
        console.error('Error cargando pacientes:', error);
    }
}

// Cargar gráfico de alertas
async function cargarGrafico() {
    try {
        const response = await fetch('/api/iot/estadisticas-alertas');
        const data = await response.json();
        
        const ctx = document.getElementById('graficoAlertas').getContext('2d');
        
        if (graficoAlertas) {
            graficoAlertas.destroy();
        }
        
        graficoAlertas = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Alertas por día',
                    data: data.valores,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Alertas IoT - Últimos 7 días' }
                }
            }
        });
    } catch (error) {
        console.error('Error cargando gráfico:', error);
    }
}

// Cargar mapa
async function cargarMapa() {
    try {
        const response = await fetch('/api/iot/ubicaciones');
        const ubicaciones = await response.json();
        
        // Usar Leaflet para el mapa (CDN)
        if (typeof L !== 'undefined') {
            const map = L.map('mapa').setView([-12.0464, -77.0428], 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            ubicaciones.forEach(ubic => {
                if (ubic.lat && ubic.lon) {
                    const marker = L.marker([ubic.lat, ubic.lon]).addTo(map);
                    marker.bindPopup(`
                        <strong>${ubic.nombres} ${ubic.apellidos}</strong><br>
                        DNI: ${ubic.dni}<br>
                        Riesgo: ${ubic.nivel_riesgo}<br>
                        <a href="/iot/paciente/${ubic.id}">Ver detalles</a>
                    `);
                }
            });
        } else {
            console.warn('Leaflet no cargado');
        }
    } catch (error) {
        console.error('Error cargando mapa:', error);
    }
}

// Ver detalles del paciente
function verDetalles(id) {
    window.location.href = `/iot/paciente/${id}`;
}

// Solicitar permisos de notificación
if (Notification.permission !== 'denied') {
    Notification.requestPermission();
}

// Inicializar dashboard
cargarResumen();
cargarAlertas();
cargarPacientes();
cargarGrafico();
cargarMapa();

// Actualizar cada 5 segundos
setInterval(() => {
    cargarAlertas();
}, 5000);

// Actualizar resumen cada 30 segundos
setInterval(() => {
    cargarResumen();
}, 30000);
</script>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.alerta-item {
    transition: all 0.3s ease;
    animation: slideIn 0.5s ease-out;
}
.alerta-item:hover {
    transform: scale(1.01);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
@endsection