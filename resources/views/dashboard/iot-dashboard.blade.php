<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wasiqhari IoT - Monitoreo Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        /* Navbar Premium */
        .navbar-premium {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Tarjetas de estadísticas */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Mapa Premium */
        .map-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        #mapa {
            height: 500px;
            width: 100%;
        }

        /* Tabla de pacientes */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .table-custom {
            border-radius: 15px;
            overflow: hidden;
        }

        .table-custom thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .table-custom tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        /* Alertas en tiempo real */
        .alertas-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-height: 500px;
            overflow-y: auto;
        }

        .alerta-item {
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            background: #fff;
            transition: all 0.3s ease;
            animation: slideIn 0.5s ease-out;
        }

        .alerta-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        /* Badges de estado */
        .badge-iot {
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-activo {
            background: #28a745;
            color: white;
        }

        .badge-inactivo {
            background: #dc3545;
            color: white;
        }

        .badge-riesgo-alto {
            background: #dc3545;
            color: white;
        }

        .badge-riesgo-medio {
            background: #ffc107;
            color: #333;
        }

        .badge-riesgo-bajo {
            background: #28a745;
            color: white;
        }

        /* Botones */
        .btn-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 20px;
            }
            
            #mapa {
                height: 300px;
            }
        }

        /* Loading spinner */
        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #667eea;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Navbar Superior -->
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-microchip" style="color: #667eea;"></i>
                <strong>Wasiqhari IoT</strong>
            </a>
            <div class="ms-auto">
                <span class="badge bg-primary">
                    <i class="fas fa-circle" style="font-size: 8px;"></i> Sistema en Tiempo Real
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Título Principal -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    <i class="fas fa-chart-line"></i> Monitoreo IoT Inteligente
                </h1>
                <p style="color: rgba(255,255,255,0.9);">Sistema de alertas tempranas y localización en tiempo real</p>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea20, #764ba220);">
                        <i class="fas fa-microchip" style="color: #667eea; font-size: 32px;"></i>
                    </div>
                    <div class="stat-number" id="dispositivos-activos">0</div>
                    <div class="stat-label">Dispositivos Activos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ff6b6b20, #ee5a2420);">
                        <i class="fas fa-bell" style="color: #ff6b6b; font-size: 32px;"></i>
                    </div>
                    <div class="stat-number" id="alertas-hoy">0</div>
                    <div class="stat-label">Alertas Hoy</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ffd93d20, #f9a82620);">
                        <i class="fas fa-exclamation-triangle" style="color: #ffd93d; font-size: 32px;"></i>
                    </div>
                    <div class="stat-number" id="pacientes-riesgo">0</div>
                    <div class="stat-label">Pacientes en Riesgo</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #6c5ce720, #a363d920);">
                        <i class="fas fa-users" style="color: #6c5ce7; font-size: 32px;"></i>
                    </div>
                    <div class="stat-number" id="total-pacientes">0</div>
                    <div class="stat-label">Adultos Monitoreados</div>
                </div>
            </div>
        </div>

        <!-- Mapa y Gráfico -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="map-container">
                    <div id="mapa"></div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="alertas-container">
                    <h5 class="mb-3">
                        <i class="fas fa-chart-line"></i> Estadísticas de Alertas
                        <button class="btn btn-sm btn-gradient float-end" onclick="actualizarGrafico()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </h5>
                    <canvas id="graficoAlertas" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Alertas en Tiempo Real -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alertas-container">
                    <h5 class="mb-3">
                        <i class="fas fa-bell"></i> Últimas Alertas en Tiempo Real
                        <span class="badge bg-danger float-end" id="alertas-count">0</span>
                    </h5>
                    <div id="alertas-lista">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Pacientes -->
        <div class="row">
            <div class="col-12">
                <div class="table-container">
                    <h5 class="mb-3">
                        <i class="fas fa-table"></i> Pacientes con Dispositivos IoT
                        <button class="btn btn-sm btn-gradient float-end" onclick="exportarExcel()">
                            <i class="fas fa-file-excel"></i> Exportar
                        </button>
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-user"></i> Nombre Completo</th>
                                    <th><i class="fas fa-id-card"></i> DNI</th>
                                    <th><i class="fas fa-microchip"></i> Dispositivo</th>
                                    <th><i class="fas fa-clock"></i> Último Contacto</th>
                                    <th><i class="fas fa-circle"></i> Estado</th>
                                    <th><i class="fas fa-chart-line"></i> Riesgo</th>
                                    <th><i class="fas fa-chart-bar"></i> Alertas</th>
                                    <th><i class="fas fa-cog"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="pacientes-tbody">
                                <tr><td colspan="9"><div class="loader"></div></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let mapa;
        let marcadores = [];
        let graficoAlertas;
        let audio = new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3');
        let ultimaAlerta = 0;

        // Inicializar mapa
        function initMapa() {
            mapa = L.map('mapa').setView([-12.0464, -77.0428], 12);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CartoDB',
                subdomains: 'abcd',
                maxZoom: 19,
                minZoom: 3
            }).addTo(mapa);
        }

        // Cargar ubicaciones en el mapa
        async function cargarUbicaciones() {
            try {
                const response = await fetch('/api/iot/ubicaciones');
                const ubicaciones = await response.json();
                
                // Limpiar marcadores existentes
                marcadores.forEach(marker => mapa.removeLayer(marker));
                marcadores = [];
                
                ubicaciones.forEach(ubic => {
                    if (ubic.lat && ubic.lon) {
                        const icono = L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                                      <i class="fas fa-heart" style="color: white; font-size: 14px;"></i>
                                  </div>`,
                            iconSize: [30, 30],
                            popupAnchor: [0, -15]
                        });
                        
                        const marker = L.marker([parseFloat(ubic.lat), parseFloat(ubic.lon)], { icon: icono })
                            .addTo(mapa)
                            .bindPopup(`
                                <div style="padding: 10px;">
                                    <strong>${ubic.nombres} ${ubic.apellidos}</strong><br>
                                    <i class="fas fa-id-card"></i> ${ubic.dni}<br>
                                    <i class="fas fa-chart-line"></i> Riesgo: ${ubic.nivel_riesgo}<br>
                                    <button class="btn btn-sm btn-primary mt-2" onclick="verDetalles(${ubic.id})">
                                        Ver detalles
                                    </button>
                                </div>
                            `);
                        marcadores.push(marker);
                    }
                });
                
                // Ajustar vista para mostrar todos los marcadores
                if (marcadores.length > 0) {
                    const group = L.featureGroup(marcadores);
                    mapa.fitBounds(group.getBounds().pad(0.1));
                }
            } catch (error) {
                console.error('Error cargando ubicaciones:', error);
            }
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

        // Cargar alertas recientes
        async function cargarAlertas() {
            try {
                const response = await fetch('/api/iot/alertas-recientes');
                const data = await response.json();
                
                const container = document.getElementById('alertas-lista');
                const alertasCount = document.getElementById('alertas-count');
                
                alertasCount.textContent = data.length;
                
                if (data.length === 0) {
                    container.innerHTML = '<div class="text-center text-muted">No hay alertas recientes</div>';
                    return;
                }
                
                let html = '';
                data.forEach(alerta => {
                    const fecha = new Date(alerta.timestamp);
                    const hora = fecha.toLocaleTimeString('es-ES');
                    const fechaStr = fecha.toLocaleDateString('es-ES');
                    
                    html += `
                        <div class="alerta-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="fas fa-bell" style="font-size: 32px; color: #dc3545;"></i>
                                </div>
                                <div class="col">
                                    <strong>${alerta.paciente}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-tachometer-alt"></i> ${alerta.fuerza_g} G | 
                                        <i class="fas fa-clock"></i> ${fechaStr} ${hora}
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-danger">${alerta.tipo_alerta}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
                
                // Reproducir sonido si hay nuevas alertas
                if (data.length > 0 && new Date(data[0].timestamp).getTime() > ultimaAlerta) {
                    audio.play().catch(e => console.log('Audio no soportado'));
                    ultimaAlerta = new Date(data[0].timestamp).getTime();
                    
                    Swal.fire({
                        title: '🚨 ¡Nueva Alerta IoT!',
                        text: `${data[0].paciente} - ${data[0].tipo_alerta} (${data[0].fuerza_g} G)`,
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Error cargando alertas:', error);
                document.getElementById('alertas-lista').innerHTML = '<div class="text-center text-danger">Error cargando alertas</div>';
            }
        }

        // Cargar pacientes
        async function cargarPacientes() {
            try {
                const response = await fetch('/api/iot/pacientes');
                const data = await response.json();
                
                const tbody = document.getElementById('pacientes-tbody');
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">No hay pacientes con dispositivos</td></tr>';
                    return;
                }
                
                tbody.innerHTML = '';
                data.forEach(paciente => {
                    const ultimoContacto = paciente.ultimo_contacto_iot ? new Date(paciente.ultimo_contacto_iot).toLocaleString() : 'Nunca';
                    const estadoClass = paciente.alertas_activas ? 'badge-activo' : 'badge-inactivo';
                    const estadoText = paciente.alertas_activas ? 'Activo' : 'Inactivo';
                    const riesgoClass = `badge-riesgo-${(paciente.nivel_riesgo || 'bajo').toLowerCase()}`;
                    
                    tbody.innerHTML += `
                        <tr>
                            <td>${paciente.id}</td>
                            <td><strong>${paciente.nombres} ${paciente.apellidos}</strong></td>
                            <td>${paciente.dni}</td>
                            <td><code>${paciente.dispositivo_id || 'No asignado'}</code></td>
                            <td><small>${ultimoContacto}</small></td>
                            <td><span class="badge-iot ${estadoClass}">${estadoText}</span></td>
                            <td><span class="badge-iot ${riesgoClass}">${paciente.nivel_riesgo || 'No definido'}</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="verAlertasPaciente(${paciente.id})">
                                    <i class="fas fa-chart-line"></i> Ver
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-gradient" onclick="verDetalles(${paciente.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error('Error cargando pacientes:', error);
            }
        }

        // Cargar gráfico
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
                            label: 'Alertas IoT',
                            data: data.valores,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#764ba2',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 10
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleColor: '#fff',
                                bodyColor: '#ddd',
                                borderColor: '#667eea',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error cargando gráfico:', error);
            }
        }

        // Función para actualizar gráfico
        function actualizarGrafico() {
            cargarGrafico();
            Swal.fire({
                icon: 'success',
                title: 'Actualizado',
                text: 'Los datos han sido actualizados',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        }

        // Ver detalles del paciente
        function verDetalles(id) {
            window.location.href = `/iot/paciente/${id}`;
        }

        // Ver alertas del paciente
        async function verAlertasPaciente(id) {
            window.location.href = `/iot/paciente/${id}`;
        }

        // Exportar a Excel
        function exportarExcel() {
            window.location.href = '/api/iot/exportar-excel';
        }

        // Inicializar dashboard
        document.addEventListener('DOMContentLoaded', () => {
            initMapa();
            cargarResumen();
            cargarAlertas();
            cargarPacientes();
            cargarGrafico();
            cargarUbicaciones();
            
            // Actualizar cada 5 segundos
            setInterval(() => {
                cargarAlertas();
                cargarResumen();
            }, 5000);
            
            // Actualizar mapa cada 30 segundos
            setInterval(() => {
                cargarUbicaciones();
            }, 30000);
            
            // Solicitar permisos de notificación
            if (Notification.permission !== 'denied') {
                Notification.requestPermission();
            }
        });
    </script>
</body>
</html>