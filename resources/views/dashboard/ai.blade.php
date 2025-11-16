@extends('layouts.dashboard')

@section('title', 'Asistente IA - WasiQhari')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-robot"></i> Asistente IA</h1>
            <p>Análisis inteligente y predicciones para optimizar tu trabajo</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="showQuickAnalysis()">
                <i class="fas fa-bolt"></i> Análisis Rápido
            </button>
        </div>
    </div>

    <div class="ai-dashboard">
        <!-- Estadísticas rápidas -->
        <div class="ai-stats-grid">
            <div class="ai-stat-card">
                <div class="stat-icon risk">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>12</h3>
                    <p>Casos de Alto Riesgo</p>
                </div>
            </div>
            
            <div class="ai-stat-card">
                <div class="stat-icon prediction">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>85%</h3>
                    <p>Precisión Predictiva</p>
                </div>
            </div>
            
            <div class="ai-stat-card">
                <div class="stat-icon optimization">
                    <i class="fas fa-route"></i>
                </div>
                <div class="stat-info">
                    <h3>35%</h3>
                    <p>Eficiencia Mejorada</p>
                </div>
            </div>
            
            <div class="ai-stat-card">
                <div class="stat-icon time">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>2.5h</h3>
                    <p>Horas Ahorradas/Semana</p>
                </div>
            </div>
        </div>

        <!-- Herramientas IA -->
        <div class="ai-tools-grid">
            <div class="ai-tool-card" onclick="analyzeRisk()">
                <div class="tool-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h4>Análisis de Riesgo</h4>
                <p>Identifica adultos mayores en situación de vulnerabilidad</p>
                <div class="tool-features">
                    <span class="feature-tag">Priorización</span>
                    <span class="feature-tag">Alertas</span>
                    <span class="feature-tag">Recomendaciones</span>
                </div>
            </div>
            
            <div class="ai-tool-card" onclick="generateReport()">
                <div class="tool-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h4>Reportes Inteligentes</h4>
                <p>Genera informes automáticos con insights accionables</p>
                <div class="tool-features">
                    <span class="feature-tag">PDF/Excel</span>
                    <span class="feature-tag">Gráficos</span>
                    <span class="feature-tag">Tendencias</span>
                </div>
            </div>
            
            <div class="ai-tool-card" onclick="predictNeeds()">
                <div class="tool-icon">
                    <i class="fas fa-crystal-ball"></i>
                </div>
                <h4>Predicción de Necesidades</h4>
                <p>Anticipa requerimientos futuros basado en datos históricos</p>
                <div class="tool-features">
                    <span class="feature-tag">Forecasting</span>
                    <span class="feature-tag">Inventario</span>
                    <span class="feature-tag">Planificación</span>
                </div>
            </div>
            
            <div class="ai-tool-card" onclick="optimizeRoutes()">
                <div class="tool-icon">
                    <i class="fas fa-route"></i>
                </div>
                <h4>Optimización de Rutas</h4>
                <p>Planifica visitas eficientes minimizando tiempo y recursos</p>
                <div class="tool-features">
                    <span class="feature-tag">GPS</span>
                    <span class="feature-tag">Tiempo Real</span>
                    <span class="feature-tag">Ahorro</span>
                </div>
            </div>
        </div>

        <!-- Chat IA Integrado -->
        <div class="ai-chat-section">
            <div class="section-header">
                <h3><i class="fas fa-comments"></i> Chat con Asistente IA</h3>
                <p>Haz preguntas específicas sobre tus datos y operaciones</p>
            </div>
            
            <div class="chat-container">
                <div class="chat-messages" id="dashboardChatMessages">
                    <div class="message ai-message">
                        <div class="message-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="message-content">
                            <p>¡Hola! Soy tu asistente IA de WasiQhari. Puedo ayudarte a analizar datos, generar reportes, predecir necesidades y optimizar rutas. ¿En qué te puedo ayudar hoy?</p>
                        </div>
                    </div>
                </div>
                
                <div class="chat-input-container">
                    <div class="quick-questions">
                        <button class="quick-btn" onclick="askQuickQuestion('¿Cuáles son los casos más urgentes?')">
                            Casos urgentes
                        </button>
                        <button class="quick-btn" onclick="askQuickQuestion('Necesito un reporte del mes')">
                            Reporte mensual
                        </button>
                        <button class="quick-btn" onclick="askQuickQuestion('Optimizar rutas para hoy')">
                            Rutas de hoy
                        </button>
                    </div>
                    
                    <div class="chat-input">
                        <input type="text" id="dashboardAiInput" placeholder="Escribe tu pregunta para la IA...">
                        <button onclick="sendDashboardMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.ai-dashboard {
    padding: 20px;
}

.ai-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.ai-stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s ease;
}

.ai-stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.risk { background: #e74c3c; }
.stat-icon.prediction { background: #f39c12; }
.stat-icon.optimization { background: #27ae60; }
.stat-icon.time { background: #3498db; }

.stat-info h3 {
    margin: 0;
    font-size: 2rem;
    color: var(--dark-color);
}

.stat-info p {
    margin: 5px 0 0 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

.ai-tools-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.ai-tool-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.ai-tool-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary-color);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
}

.tool-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary-color), #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    margin-bottom: 20px;
}

.ai-tool-card h4 {
    margin: 0 0 10px 0;
    color: var(--dark-color);
    font-size: 1.2rem;
}

.ai-tool-card p {
    color: var(--text-color);
    margin-bottom: 20px;
    line-height: 1.5;
}

.tool-features {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.feature-tag {
    background: #f8f9fa;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    color: var(--text-light);
    border: 1px solid #e9ecef;
}

.ai-chat-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.section-header {
    padding: 25px 30px;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.section-header h3 {
    margin: 0 0 5px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header p {
    margin: 0;
    opacity: 0.9;
}

.chat-container {
    padding: 0;
}

.chat-messages {
    height: 400px;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.message {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    flex-shrink: 0;
}

.ai-message .message-avatar {
    background: #3498db;
}

.user-message .message-avatar {
    background: var(--primary-color);
}

.message-content {
    flex: 1;
    background: white;
    padding: 15px 20px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.ai-message .message-content {
    border-top-left-radius: 5px;
}

.user-message .message-content {
    border-top-right-radius: 5px;
    background: var(--primary-color);
    color: white;
}

.message-content p {
    margin: 0;
    line-height: 1.5;
}

.quick-questions {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.quick-btn {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.chat-input-container {
    padding: 20px;
}

.chat-input {
    display: flex;
    gap: 15px;
}

.chat-input input {
    flex: 1;
    padding: 15px 20px;
    border: 1px solid #e0e0e0;
    border-radius: 25px;
    font-size: 1rem;
    outline: none;
}

.chat-input input:focus {
    border-color: var(--primary-color);
}

.chat-input button {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.chat-input button:hover {
    background: #2980b9;
    transform: scale(1.05);
}

/* Modo oscuro para la sección IA */
body.dark-mode .ai-stat-card,
body.dark-mode .ai-tool-card,
body.dark-mode .ai-chat-section {
    background: #1e1e1e;
    border-color: #333;
}

body.dark-mode .ai-tool-card:hover {
    border-color: var(--primary-color);
}

body.dark-mode .feature-tag {
    background: #2d2d2d;
    border-color: #444;
    color: #b0b0b0;
}

body.dark-mode .chat-messages {
    background: #2d2d2d;
}

body.dark-mode .quick-btn {
    background: #2d2d2d;
    border-color: #444;
    color: #e0e0e0;
}

body.dark-mode .quick-btn:hover {
    background: var(--primary-color);
    color: white;
}

body.dark-mode .chat-input input {
    background: #2d2d2d;
    border-color: #444;
    color: #e0e0e0;
}

@media (max-width: 768px) {
    .ai-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .ai-tools-grid {
        grid-template-columns: 1fr;
    }
    
    .ai-stat-card {
        padding: 20px;
    }
    
    .quick-questions {
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Funciones para el dashboard de IA
function showQuickAnalysis() {
    Swal.fire({
        title: '🔍 Análisis Rápido IA',
        html: `
            <div class="quick-analysis">
                <div class="analysis-item">
                    <i class="fas fa-heartbeat text-danger"></i>
                    <div>
                        <strong>12 Casos Críticos</strong>
                        <p>Requieren atención inmediata</p>
                    </div>
                </div>
                <div class="analysis-item">
                    <i class="fas fa-route text-success"></i>
                    <div>
                        <strong>Rutas Optimizadas</strong>
                        <p>35% más eficientes</p>
                    </div>
                </div>
                <div class="analysis-item">
                    <i class="fas fa-box text-warning"></i>
                    <div>
                        <strong>Inventario Bajo</strong>
                        <p>+15% alimentos necesarios</p>
                    </div>
                </div>
            </div>
        `,
        confirmButtonText: 'Ver Detalles',
        showCancelButton: true,
        cancelButtonText: 'Cerrar'
    });
}

function analyzeRisk() {
    Swal.fire({
        title: '🔍 Analizando Riesgos...',
        html: `
            <div class="analysis-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 75%"></div>
                </div>
                <p>Evaluando casos críticos y priorizando intervenciones</p>
            </div>
        `,
        showConfirmButton: false,
        timer: 2000
    }).then(() => {
        Swal.fire({
            title: '🎯 Análisis de Riesgo Completado',
            html: `
                <div class="risk-results">
                    <div class="risk-item high-risk">
                        <strong>3 Casos de Alto Riesgo</strong>
                        <p>Requieren intervención inmediata</p>
                    </div>
                    <div class="risk-item medium-risk">
                        <strong>8 Casos de Riesgo Medio</strong>
                        <p>Necesitan seguimiento cercano</p>
                    </div>
                    <div class="risk-item low-risk">
                        <strong>15 Casos Estables</strong>
                        <p>Continuar con monitoreo regular</p>
                    </div>
                </div>
            `,
            confirmButtonText: 'Ver Detalles',
            showCancelButton: true
        });
    });
}

function generateReport() {
    Swal.fire({
        title: '📊 Generando Reporte Inteligente',
        html: `
            <div class="report-options">
                <label class="checkbox-container">
                    <input type="checkbox" checked> Incluir análisis predictivo
                </label>
                <label class="checkbox-container">
                    <input type="checkbox" checked> Incluir recomendaciones IA
                </label>
                <label class="checkbox-container">
                    <input type="checkbox"> Incluir datos históricos completos
                </label>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Generar Reporte',
        preConfirm: () => {
            return {
                predictivo: true,
                recomendaciones: true,
                historico: false
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '📄 Reporte Generado',
                html: `
                    <div class="report-success">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                        <p>El reporte inteligente ha sido generado con:</p>
                        <ul>
                            <li>✅ Análisis predictivo de tendencias</li>
                            <li>✅ Recomendaciones accionables</li>
                            <li>✅ Gráficos interactivos</li>
                            <li>✅ Insights de IA</li>
                        </ul>
                    </div>
                `,
                confirmButtonText: 'Descargar PDF'
            });
        }
    });
}

function predictNeeds() {
    Swal.fire({
        title: '🔮 Prediciendo Necesidades Futuras',
        html: `
            <div class="prediction-loading">
                <i class="fas fa-crystal-ball fa-spin fa-2x mb-3"></i>
                <p>Analizando patrones históricos y tendencias...</p>
            </div>
        `,
        showConfirmButton: false,
        timer: 2500
    }).then(() => {
        Swal.fire({
            title: '📈 Predicciones para el Próximo Mes',
            html: `
                <div class="predictions-grid">
                    <div class="prediction-card">
                        <i class="fas fa-utensils text-warning"></i>
                        <h4>+25%</h4>
                        <p>Demanda de alimentos</p>
                    </div>
                    <div class="prediction-card">
                        <i class="fas fa-briefcase-medical text-danger"></i>
                        <h4>+18%</h4>
                        <p>Atenciones médicas</p>
                    </div>
                    <div class="prediction-card">
                        <i class="fas fa-home text-primary"></i>
                        <h4>+12%</h4>
                        <p>Visitas requeridas</p>
                    </div>
                </div>
                <div class="prediction-insights">
                    <h5>💡 Insights de IA:</h5>
                    <p>Se recomienda aumentar inventario de medicamentos básicos y programar visitas preventivas en zonas de alta vulnerabilidad.</p>
                </div>
            `,
            confirmButtonText: 'Planificar Acciones'
        });
    });
}

function optimizeRoutes() {
    Swal.fire({
        title: '🗺️ Optimizando Rutas',
        html: `
            <div class="optimization-loading">
                <i class="fas fa-route fa-spin fa-2x mb-3"></i>
                <p>Calculando rutas más eficientes...</p>
            </div>
        `,
        showConfirmButton: false,
        timer: 3000
    }).then(() => {
        Swal.fire({
            title: '✅ Rutas Optimizadas',
            html: `
                <div class="route-results">
                    <div class="route-improvement">
                        <i class="fas fa-chart-line text-success"></i>
                        <div>
                            <strong>35% más eficiente</strong>
                            <p>Reducción en tiempo y distancia</p>
                        </div>
                    </div>
                    <div class="route-details">
                        <h5>📋 Plan de Rutas:</h5>
                        <ul>
                            <li><strong>Ruta 1:</strong> Cusco Centro (3 visitas, 2.5h)</li>
                            <li><strong>Ruta 2:</strong> San Sebastián (4 visitas, 3h)</li>
                            <li><strong>Ruta 3:</strong> Wanchaq (2 visitas, 1.5h)</li>
                        </ul>
                    </div>
                </div>
            `,
            confirmButtonText: 'Ver Mapa',
            showCancelButton: true
        });
    });
}

function askQuickQuestion(question) {
    addDashboardMessage(question, 'user');
    
    setTimeout(() => {
        let response = '';
        if (question.includes('urgente')) {
            response = "Los casos más urgentes son:\n• Martina Quispe (85) - Cusco Centro\n• Juan Mamani (78) - San Sebastián\n• Rosa Condori (82) - Wanchaq\nRecomiendo visitas prioritarias hoy mismo.";
        } else if (question.includes('reporte')) {
            response = "Puedo generarte un reporte del mes actual con:\n• 45 visitas completadas\n• 12 casos en seguimiento\n• 8 nuevos voluntarios\n• 85% de satisfacción\n¿Quieres que lo genere en PDF o Excel?";
        } else if (question.includes('rutas')) {
            response = "He optimizado las rutas para hoy:\n• Ruta 1: Cusco Centro (3 casos, 2.5h)\n• Ruta 2: San Sebastián (4 casos, 3h)\n• Ruta 3: Wanchaq (2 casos, 1.5h)\n¿Deseas ver el mapa detallado?";
        } else {
            response = "¡Claro! Puedo ayudarte con eso. ¿Necesitas información más específica sobre algún aspecto en particular?";
        }
        
        addDashboardMessage(response, 'ai');
    }, 1000);
}

function sendDashboardMessage() {
    const input = document.getElementById('dashboardAiInput');
    const message = input.value.trim();
    
    if (message) {
        addDashboardMessage(message, 'user');
        input.value = '';
        
        setTimeout(() => {
            addDashboardMessage("He procesado tu solicitud. ¿Te gustaría que profundice en algún aspecto específico o generé un reporte detallado?", 'ai');
        }, 1500);
    }
}

function addDashboardMessage(content, type) {
    const chat = document.getElementById('dashboardChatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    
    messageDiv.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-${type === 'user' ? 'user' : 'robot'}"></i>
        </div>
        <div class="message-content">
            <p>${content.replace(/\n/g, '<br>')}</p>
        </div>
    `;
    
    chat.appendChild(messageDiv);
    chat.scrollTop = chat.scrollHeight;
}

// Permitir Enter en el chat del dashboard
document.getElementById('dashboardAiInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendDashboardMessage();
    }
});

// Estilos adicionales para los modales
const additionalStyles = `
.quick-analysis .analysis-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    margin: 10px 0;
    background: #f8f9fa;
    border-radius: 10px;
}

.analysis-progress .progress-bar {
    width: 100%;
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    overflow: hidden;
    margin: 15px 0;
}

.analysis-progress .progress-fill {
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    transition: width 0.3s ease;
}

.risk-results .risk-item {
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    border-left: 4px solid;
}

.risk-results .high-risk {
    background: #fff5f5;
    border-left-color: #e74c3c;
}

.risk-results .medium-risk {
    background: #fffbf0;
    border-left-color: #f39c12;
}

.risk-results .low-risk {
    background: #f0f7ff;
    border-left-color: #3498db;
}

.report-options .checkbox-container {
    display: block;
    margin: 10px 0;
}

.prediction-loading, .optimization-loading {
    text-align: center;
    padding: 20px;
}

.predictions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin: 20px 0;
}

.prediction-card {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.prediction-card i {
    font-size: 2rem;
    margin-bottom: 10px;
}

.prediction-card h4 {
    margin: 5px 0;
    font-size: 1.5rem;
}

.prediction-insights {
    background: #e3f2fd;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

.route-improvement {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #e8f5e8;
    border-radius: 8px;
    margin: 15px 0;
}

.route-details ul {
    text-align: left;
    margin: 15px 0;
}

.route-details li {
    margin: 8px 0;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 5px;
}
`;

// Inject additional styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
</script>
@endpush