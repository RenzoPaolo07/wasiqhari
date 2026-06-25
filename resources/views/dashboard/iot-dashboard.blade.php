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

        /* Tarjetas de sensores */
        .sensor-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .sensor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .sensor-value {
            font-size: 28px;
            font-weight: bold;
            margin: 15px 0;
        }

        .sensor-unit {
            font-size: 14px;
            color: #666;
        }

        /* Mapa Premium */
        .map-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        #mapa {
            height: 450px;
            width: 100%;
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

        /* Badges */
        .badge-iot {
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-activo { background: #28a745; color: white; }
        .badge-inactivo { background: #dc3545; color: white; }
        .badge-peligro { background: #dc3545; color: white; animation: pulse 1s infinite; }
        .badge-cuidado { background: #ffc107; color: #333; }
        .badge-normal { background: #28a745; color: white; }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        /* Gráficos */
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
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

        /* Tabla */
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

        /* Indicadores en tiempo real */
        .live-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #28a745;
            animation: pulse 1s infinite;
            margin-right: 8px;
        }

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

        /* Estilos para el Arduino */
        .arduino-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .arduino-card .card-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            padding: 15px 20px;
        }

        .arduino-card .sensor-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            height: auto;
            transition: all 0.3s ease;
        }

        .arduino-card .sensor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .arduino-card .sensor-value {
            font-size: 24px;
            margin: 10px 0;
        }

        .progress {
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.5s ease;
            font-size: 12px;
            line-height: 20px;
        }

        #sos-alert, #impacto-alert {
            animation: flashAlert 0.5s ease-in-out;
        }

        @keyframes flashAlert {
            0% { opacity: 0; transform: scale(0.9); }
            50% { opacity: 1; transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }

        .badge-riesgo-bajo { background: #28a745; color: white; }
        .badge-riesgo-medio { background: #ffc107; color: #333; }
        .badge-riesgo-alto { background: #dc3545; color: white; }

        /* Estado de conexión del Arduino */
        .arduino-status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 1.5s infinite;
        }

        .arduino-status-indicator.online {
            background: #28a745;
        }

        .arduino-status-indicator.offline {
            background: #dc3545;
            animation: none;
        }

        .arduino-status-indicator.error {
            background: #ffc107;
            animation: none;
        }

        @media (max-width: 768px) {
            .stat-card, .sensor-card { margin-bottom: 20px; }
            #mapa { height: 300px; }
            .arduino-card .sensor-card { margin-bottom: 10px; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-microchip" style="color: #667eea;"></i>
                <strong>Wasiqhari IoT</strong>
                <span class="live-indicator"></span> <small class="text-muted">Monitoreo en tiempo real</small>
            </a>
            <div class="ms-auto">
                <span class="badge bg-primary">
                    <i class="fas fa-clock"></i> <span id="reloj"></span>
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Título Principal -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    <i class="fas fa-chart-line"></i> Centro de Monitoreo IoT Inteligente
                </h1>
                <p style="color: rgba(255,255,255,0.9);">Sistema de alertas tempranas, localización y monitoreo ambiental</p>
            </div>
        </div>

        <!-- Fila 1: Tarjetas de Métricas Principales -->
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

        <!-- ============================================ -->
        <!-- SECCIÓN: Estado del Arduino con API -->
        <!-- ============================================ -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="arduino-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-microchip"></i> 
                            <strong>Arduino Uno - Monitoreo en Tiempo Real</strong>
                        </div>
                        <div>
                            <span class="badge bg-light text-success" id="arduino-status">
                                <span class="arduino-status-indicator online" id="arduino-status-indicator"></span>
                                <span id="arduino-status-text">Conectado</span>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Temperatura -->
                            <div class="col-md-3 text-center mb-3">
                                <div class="sensor-card">
                                    <i class="fas fa-temperature-high" style="font-size: 30px; color: #ff6b6b;"></i>
                                    <div class="sensor-value">
                                        <span id="arduino-temp">--</span>°C
                                    </div>
                                    <div class="sensor-unit">Temperatura</div>
                                </div>
                            </div>
                            
                            <!-- Humedad -->
                            <div class="col-md-3 text-center mb-3">
                                <div class="sensor-card">
                                    <i class="fas fa-water" style="font-size: 30px; color: #4dabf7;"></i>
                                    <div class="sensor-value">
                                        <span id="arduino-hum">--</span>%
                                    </div>
                                    <div class="sensor-unit">Humedad</div>
                                </div>
                            </div>
                            
                            <!-- Distancia -->
                            <div class="col-md-3 text-center mb-3">
                                <div class="sensor-card">
                                    <i class="fas fa-arrows-alt-h" style="font-size: 30px; color: #fcc419;"></i>
                                    <div class="sensor-value">
                                        <span id="arduino-dist">--</span> cm
                                    </div>
                                    <div class="sensor-unit">Distancia</div>
                                </div>
                            </div>
                            
                            <!-- Luz -->
                            <div class="col-md-3 text-center mb-3">
                                <div class="sensor-card">
                                    <i class="fas fa-sun" style="font-size: 30px; color: #ffd43b;"></i>
                                    <div class="sensor-value">
                                        <span id="arduino-luz">--</span>
                                    </div>
                                    <div class="sensor-unit">Lux (LDR)</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Acelerómetro -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-center mb-3">Acelerómetro (MPU6050)</h6>
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <label class="text-muted small">Eje X</label>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" id="accel-x-bar" style="width: 50%; background: #ff6b6b;">50%</div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <label class="text-muted small">Eje Y</label>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" id="accel-y-bar" style="width: 50%; background: #4dabf7;">50%</div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <label class="text-muted small">Eje Z</label>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" id="accel-z-bar" style="width: 50%; background: #51cf66;">50%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estado SOS -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-danger" id="sos-alert" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>¡ALERTA SOS ACTIVADA!</strong>
                                </div>
                                <div class="alert alert-warning" id="impacto-alert" style="display: none;">
                                    <i class="fas fa-bolt"></i> 
                                    <strong>¡IMPACTO DETECTADO!</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================ -->
        <!-- FIN SECCIÓN ARDUINO -->
        <!-- ============================================ -->

        <!-- Fila 2: Sensores en Tiempo Real -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="sensor-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-tachometer-alt"></i> Acelerómetro (MPU6050)</h6>
                        <span class="badge bg-success" id="acelerometro-status">En línea</span>
                    </div>
                    <div class="sensor-value">
                        <span id="fuerza-g">0.00</span> <span class="sensor-unit">G</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar" id="fuerza-bar" style="width: 0%; background: linear-gradient(90deg, #28a745, #dc3545);"></div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-center">
                            <small class="text-muted">Eje X</small>
                            <div><strong id="accel-x">0.00</strong> <small>G</small></div>
                        </div>
                        <div class="col-4 text-center">
                            <small class="text-muted">Eje Y</small>
                            <div><strong id="accel-y">0.00</strong> <small>G</small></div>
                        </div>
                        <div class="col-4 text-center">
                            <small class="text-muted">Eje Z</small>
                            <div><strong id="accel-z">0.00</strong> <small>G</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="sensor-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-temperature-high"></i> Sensor Ambiental (DHT22)</h6>
                        <span class="badge bg-success" id="dht-status">En línea</span>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="sensor-value">
                                <span id="temperatura">--</span> <span class="sensor-unit">°C</span>
                            </div>
                            <div class="sensor-value" style="font-size: 14px;">
                                <span id="sensacion-termica">--</span> <span class="sensor-unit">°C</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="sensor-value">
                                <span id="humedad">--</span> <span class="sensor-unit">%</span>
                            </div>
                            <div class="sensor-value" style="font-size: 14px;">
                                <span id="punto-rocio">--</span> <span class="sensor-unit">°C</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="sensor-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-arrows-alt"></i> Distancia (HC-SR04)</h6>
                        <span class="badge bg-success" id="distancia-status">En línea</span>
                    </div>
                    <div class="sensor-value text-center">
                        <span id="distancia">0</span> <span class="sensor-unit">cm</span>
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar" id="distancia-bar" style="width: 0%; background: linear-gradient(90deg, #28a745, #ffc107, #dc3545);"></div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted" id="distancia-mensaje">Sin obstáculos cercanos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila 3: Luz y Botón SOS -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="sensor-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-sun"></i> Sensor de Luz (LDR)</h6>
                        <span class="badge bg-success" id="ldr-status">En línea</span>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="sensor-value">
                                <span id="luz">0</span> <span class="sensor-unit">lux</span>
                            </div>
                            <span class="badge" id="luz-badge">Oscuro</span>
                        </div>
                        <div class="col-6 text-center">
                            <i class="fas fa-lightbulb" style="font-size: 48px; color: #ffc107;"></i>
                            <div class="mt-2">
                                <span id="luz-recomendacion">Encender luces</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sensor-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6><i class="fas fa-hand-paper"></i> Botón de Pánico (SOS)</h6>
                        <span class="badge bg-success" id="sos-status">En línea</span>
                    </div>
                    <div class="text-center">
                        <div class="sos-button" id="sos-button" style="cursor: pointer;">
                            <i class="fas fa-bell" style="font-size: 48px; color: #dc3545;"></i>
                            <div class="sensor-value" style="font-size: 16px;">Presiona para probar</div>
                        </div>
                        <div id="ultimo-sos" class="mt-2">
                            <small class="text-muted">Última alerta: --</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fila 4: Mapa y Alertas -->
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
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </h5>
                    <canvas id="graficoAlertas" height="250"></canvas>
                    <hr>
                    <h6 class="mb-3">Alertas por Tipo</h6>
                    <canvas id="graficoPie" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Fila 5: Alertas Recientes -->
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

        <!-- Fila 6: Tabla de Pacientes -->
        <div class="row">
            <div class="col-12">
                <div class="chart-container">
                    <h5 class="mb-3">
                        <i class="fas fa-table"></i> Pacientes con Dispositivos IoT
                        <button class="btn btn-sm btn-gradient float-end" onclick="exportarDatos()">
                            <i class="fas fa-download"></i> Exportar Datos
                        </button>
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-user"></i> Nombre</th>
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
        let graficoPie;
        let audio = new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3');
        let ultimaAlerta = 0;
        let arduinoInterval;

        // Reloj en tiempo real
        function actualizarReloj() {
            const ahora = new Date();
            document.getElementById('reloj').textContent = ahora.toLocaleTimeString('es-ES');
        }
        setInterval(actualizarReloj, 1000);
        actualizarReloj();

        // Inicializar mapa
        function initMapa() {
            mapa = L.map('mapa').setView([-12.0464, -77.0428], 12);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CartoDB',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(mapa);
        }

        // ============================================
        // FUNCIONES PARA EL ARDUINO CON API
        // ============================================
        
        function reproducirAlerta() {
            try {
                audio.play();
            } catch(e) {
                console.log('Error al reproducir audio:', e);
            }
        }

        // Función mejorada para obtener datos reales del ESP32
        function simularArduino() {
            fetch('/api/iot/datos-reales')
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Error en la respuesta: ' + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data && data.sensores) {
                        const sensores = data.sensores;
                        
                        // Actualizar estado del Arduino
                        const statusIndicator = document.getElementById('arduino-status-indicator');
                        const statusText = document.getElementById('arduino-status-text');
                        const statusBadge = document.getElementById('arduino-status');
                        
                        if (data.estado === 'conectado') {
                            statusIndicator.className = 'arduino-status-indicator online';
                            statusText.textContent = 'Conectado';
                            statusBadge.className = 'badge bg-light text-success';
                        } else {
                            statusIndicator.className = 'arduino-status-indicator offline';
                            statusText.textContent = 'Sin datos';
                            statusBadge.className = 'badge bg-light text-danger';
                        }
                        
                        // Actualizar valores de sensores
                        document.getElementById('arduino-temp').textContent = sensores.temperatura !== null ? sensores.temperatura.toFixed(1) : '--';
                        document.getElementById('arduino-hum').textContent = sensores.humedad !== null ? sensores.humedad.toFixed(1) : '--';
                        document.getElementById('arduino-dist').textContent = sensores.distancia !== null ? sensores.distancia : '--';
                        document.getElementById('arduino-luz').textContent = sensores.luz !== null ? sensores.luz : '--';
                        
                        // Actualizar acelerómetro
                        if (sensores.acelerometro) {
                            const accel = sensores.acelerometro;
                            const xPercent = ((accel.x || 0) + 1) * 50;
                            const yPercent = ((accel.y || 0) + 1) * 50;
                            const zPercent = (accel.z || 0) * 50;
                            
                            document.getElementById('accel-x-bar').style.width = Math.min(Math.max(xPercent, 0), 100) + '%';
                            document.getElementById('accel-x-bar').textContent = Math.round(Math.min(Math.max(xPercent, 0), 100)) + '%';
                            
                            document.getElementById('accel-y-bar').style.width = Math.min(Math.max(yPercent, 0), 100) + '%';
                            document.getElementById('accel-y-bar').textContent = Math.round(Math.min(Math.max(yPercent, 0), 100)) + '%';
                            
                            document.getElementById('accel-z-bar').style.width = Math.min(Math.max(zPercent, 0), 100) + '%';
                            document.getElementById('accel-z-bar').textContent = Math.round(Math.min(Math.max(zPercent, 0), 100)) + '%';
                        }
                        
                        // Actualizar alertas
                        const sosAlert = document.getElementById('sos-alert');
                        const impactoAlert = document.getElementById('impacto-alert');
                        
                        if (sensores.sos) {
                            sosAlert.style.display = 'block';
                            try { audio.play(); } catch(e) {}
                        } else {
                            sosAlert.style.display = 'none';
                        }
                        
                        if (sensores.impacto) {
                            impactoAlert.style.display = 'block';
                        } else {
                            impactoAlert.style.display = 'none';
                        }
                    }
                })
                .catch(err => {
                    console.error('Error cargando datos del ESP32:', err);
                    // Mostrar estado de error
                    const statusIndicator = document.getElementById('arduino-status-indicator');
                    const statusText = document.getElementById('arduino-status-text');
                    const statusBadge = document.getElementById('arduino-status');
                    
                    statusIndicator.className = 'arduino-status-indicator error';
                    statusText.textContent = 'Error de conexión';
                    statusBadge.className = 'badge bg-light text-warning';
                });
        }

        // ============================================
        // FUNCIONES PARA LOS SENSORES SIMULADOS (BACKUP)
        // ============================================
        
        // Actualizar datos del acelerómetro (simulado local)
        function actualizarAcelerometro() {
            const fuerzaG = (Math.random() * 2).toFixed(2);
            const accelX = (Math.random() * 1 - 0.5).toFixed(2);
            const accelY = (Math.random() * 1 - 0.5).toFixed(2);
            const accelZ = (Math.random() * 1 + 0.8).toFixed(2);
            
            document.getElementById('fuerza-g').textContent = fuerzaG;
            document.getElementById('accel-x').textContent = accelX;
            document.getElementById('accel-y').textContent = accelY;
            document.getElementById('accel-z').textContent = accelZ;
            
            const porcentaje = (fuerzaG / 4) * 100;
            document.getElementById('fuerza-bar').style.width = Math.min(porcentaje, 100) + '%';
            
            let color = '#28a745';
            if (fuerzaG > 1.5) color = '#ffc107';
            if (fuerzaG > 2.5) color = '#dc3545';
            document.getElementById('fuerza-bar').style.background = `linear-gradient(90deg, #28a745, ${color})`;
        }

        // Actualizar datos ambientales
        function actualizarAmbiente() {
            const temp = (Math.random() * 15 + 20).toFixed(1);
            const hum = (Math.random() * 40 + 40).toFixed(0);
            
            document.getElementById('temperatura').textContent = temp;
            document.getElementById('humedad').textContent = hum;
            
            let sensacion = temp;
            if (hum > 70) sensacion = (temp - 2).toFixed(1);
            if (hum < 40) sensacion = (temp + 1).toFixed(1);
            document.getElementById('sensacion-termica').textContent = sensacion;
            
            const puntoRocio = (temp - ((100 - hum) / 5)).toFixed(1);
            document.getElementById('punto-rocio').textContent = puntoRocio;
        }

        // Actualizar distancia
        function actualizarDistancia() {
            const distancia = Math.floor(Math.random() * 200);
            document.getElementById('distancia').textContent = distancia;
            
            const porcentaje = (distancia / 200) * 100;
            document.getElementById('distancia-bar').style.width = porcentaje + '%';
            
            let mensaje = 'Sin obstáculos cercanos';
            let color = '#28a745';
            if (distancia < 100) {
                mensaje = '⚠️ Objeto cercano a ' + distancia + ' cm';
                color = '#ffc107';
            }
            if (distancia < 50) {
                mensaje = '🚨 ¡PELIGRO! Objeto muy cercano';
                color = '#dc3545';
            }
            if (distancia < 20) {
                mensaje = '🔴 ¡ALERTA MÁXIMA! Colisión inminente';
                color = '#dc3545';
            }
            document.getElementById('distancia-mensaje').textContent = mensaje;
            document.getElementById('distancia-bar').style.background = `linear-gradient(90deg, #28a745, ${color}, #dc3545)`;
        }

        // Actualizar sensor de luz
        function actualizarLuz() {
            const luz = Math.floor(Math.random() * 1000);
            document.getElementById('luz').textContent = luz;
            
            let mensaje = 'Oscuro';
            let badge = 'badge bg-secondary';
            let recomendacion = '💡 Encender luces';
            
            if (luz > 200) {
                mensaje = 'Poca luz';
                badge = 'badge bg-warning';
                recomendacion = '💡 Luces recomendadas';
            }
            if (luz > 500) {
                mensaje = 'Ambiente iluminado';
                badge = 'badge bg-success';
                recomendacion = '✅ Luz adecuada';
            }
            if (luz > 800) {
                mensaje = 'Muy brillante';
                badge = 'badge bg-info';
                recomendacion = '🕶️ Reducir exposición';
            }
            
            document.getElementById('luz-badge').className = badge;
            document.getElementById('luz-badge').textContent = mensaje;
            document.getElementById('luz-recomendacion').innerHTML = recomendacion;
        }

        // ============================================
        // FUNCIONES DE CARGA DE DATOS
        // ============================================

        // Cargar resumen
        async function cargarResumen() {
            try {
                const response = await fetch('/api/iot/resumen');
                const data = await response.json();
                document.getElementById('dispositivos-activos').textContent = data.dispositivos_activos || 0;
                document.getElementById('alertas-hoy').textContent = data.alertas_hoy || 0;
                document.getElementById('pacientes-riesgo').textContent = data.pacientes_riesgo || 0;
                document.getElementById('total-pacientes').textContent = data.total_pacientes || 0;
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Cargar alertas
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
                                        <i class="fas fa-clock"></i> ${fecha.toLocaleString()}
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
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Cargar pacientes
        async function cargarPacientes() {
            try {
                const response = await fetch('/api/iot/pacientes');
                const data = await response.json();
                
                const tbody = document.getElementById('pacientes-tbody');
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center">No hay pacientes</td></tr>';
                    return;
                }
                
                tbody.innerHTML = '';
                data.forEach(paciente => {
                    const riesgoClass = `badge-riesgo-${(paciente.nivel_riesgo || 'bajo').toLowerCase()}`;
                    tbody.innerHTML += `
                        <tr>
                            <td>${paciente.id}</td>
                            <td><strong>${paciente.nombres} ${paciente.apellidos}</strong></td>
                            <td>${paciente.dni}</td>
                            <td><code>${paciente.dispositivo_id || 'No'}</code></td>
                            <td><small>${paciente.ultimo_contacto_iot ? new Date(paciente.ultimo_contacto_iot).toLocaleString() : 'Nunca'}</small></td>
                            <td><span class="badge-iot ${paciente.alertas_activas ? 'badge-activo' : 'badge-inactivo'}">${paciente.alertas_activas ? 'Activo' : 'Inactivo'}</span></td>
                            <td><span class="badge-iot ${riesgoClass}">${paciente.nivel_riesgo || 'No'}</span></td>
                            <td><span id="alertas-${paciente.id}">--</span></td>
                            <td>
                                <button class="btn btn-sm btn-gradient" onclick="verDetalles(${paciente.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Cargar gráficos
        async function cargarGraficos() {
            try {
                const response = await fetch('/api/iot/estadisticas-alertas');
                const data = await response.json();
                
                const ctx = document.getElementById('graficoAlertas').getContext('2d');
                if (graficoAlertas) graficoAlertas.destroy();
                
                graficoAlertas = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Alertas',
                            data: data.valores,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: true }
                });
                
                // Gráfico de torta
                const pieCtx = document.getElementById('graficoPie').getContext('2d');
                if (graficoPie) graficoPie.destroy();
                
                graficoPie = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Caídas', 'SOS Manual', 'Detección'],
                        datasets: [{
                            data: [data.caidas || 0, data.sos || 0, data.detecciones || 0],
                            backgroundColor: ['#dc3545', '#ffc107', '#28a745']
                        }]
                    },
                    options: { responsive: true }
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // ============================================
        // FUNCIONES DE UTILIDAD
        // ============================================

        function verDetalles(id) {
            window.location.href = `/iot/paciente/${id}`;
        }

        function exportarDatos() {
            window.location.href = '/api/iot/exportar-excel';
        }

        function actualizarGrafico() {
            cargarGraficos();
            Swal.fire({ icon: 'success', title: 'Actualizado', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
        }

        // ============================================
        // INICIALIZACIÓN
        // ============================================

        document.addEventListener('DOMContentLoaded', () => {
            initMapa();
            cargarResumen();
            cargarAlertas();
            cargarPacientes();
            cargarGraficos();
            
            // Sensores en tiempo real (simulados localmente como backup)
            setInterval(actualizarAcelerometro, 2000);
            setInterval(actualizarAmbiente, 3000);
            setInterval(actualizarDistancia, 2000);
            setInterval(actualizarLuz, 3000);
            
            // Arduino - Obtener datos reales del ESP32 (cada 2 segundos)
            arduinoInterval = setInterval(simularArduino, 2000);
            
            // Actualizar datos generales cada 5 segundos
            setInterval(() => { 
                cargarAlertas(); 
                cargarResumen(); 
            }, 5000);
            
            // Botón SOS demo
            document.getElementById('sos-button')?.addEventListener('click', () => {
                Swal.fire('🚨 SOS Enviado', 'Se ha enviado una alerta de emergencia', 'error');
                
                // También activar alerta en Arduino
                document.getElementById('sos-alert').style.display = 'block';
                try { audio.play(); } catch(e) {}
                setTimeout(() => {
                    document.getElementById('sos-alert').style.display = 'none';
                }, 5000);
                
                // Actualizar timestamp
                const ahora = new Date();
                document.getElementById('ultimo-sos').innerHTML = `<small class="text-danger">Última alerta: ${ahora.toLocaleTimeString()}</small>`;
            });
            
            console.log('🚀 Sistema IoT Iniciado correctamente');
            console.log('📡 Monitoreando Arduino en: /api/iot/datos-reales');
        });
    </script>
</body>
</html>