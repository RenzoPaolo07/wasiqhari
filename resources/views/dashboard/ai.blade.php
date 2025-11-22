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
/* Estilos Generales IA Dashboard */
.ai-dashboard { padding: 20px; }

/* Stats Grid */
.ai-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
.ai-stat-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 20px; transition: transform 0.3s ease; border: 1px solid #eee; }
.ai-stat-card:hover { transform: translateY(-5px); }
.stat-icon { width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }
.stat-icon.risk { background: #e74c3c; }
.stat-icon.prediction { background: #f39c12; }
.stat-icon.optimization { background: #27ae60; }
.stat-icon.time { background: #3498db; }
.stat-info h3 { margin: 0; font-size: 2rem; color: var(--dark-color); }
.stat-info p { margin: 5px 0 0 0; color: var(--text-light); font-size: 0.9rem; }

/* Tools Grid */
.ai-tools-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 40px; }
.ai-tool-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; }
.ai-tool-card:hover { transform: translateY(-5px); border-color: var(--primary-color); box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2); }
.tool-icon { width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary-color), #764ba2); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: white; margin-bottom: 20px; }
.ai-tool-card h4 { margin: 0 0 10px 0; color: var(--dark-color); font-size: 1.2rem; }
.ai-tool-card p { color: var(--text-color); margin-bottom: 20px; line-height: 1.5; }
.tool-features { display: flex; gap: 8px; flex-wrap: wrap; }
.feature-tag { background: #f8f9fa; padding: 4px 12px; border-radius: 15px; font-size: 0.8rem; color: var(--text-light); border: 1px solid #e9ecef; }

/* Chat Section */
.ai-chat-section { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #eee; }
.section-header { padding: 25px 30px; border-bottom: 1px solid #f0f0f0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.section-header h3 { margin: 0 0 5px 0; display: flex; align-items: center; gap: 10px; font-size: 1.3rem; }
.section-header p { margin: 0; opacity: 0.9; font-size: 0.95rem; }
.chat-container { padding: 0; }
.chat-messages { height: 400px; overflow-y: auto; padding: 20px; background: #f8f9fa; }

/* Mensajes */
.message { display: flex; gap: 15px; margin-bottom: 20px; }
.message-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: white; flex-shrink: 0; }
.ai-message .message-avatar { background: #3498db; }
.user-message .message-avatar { background: var(--primary-color); }
.message-content { flex: 1; background: white; padding: 15px 20px; border-radius: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); max-width: 80%; }
.ai-message .message-content { border-top-left-radius: 5px; }
.user-message { flex-direction: row-reverse; }
.user-message .message-content { border-top-right-radius: 5px; background: var(--primary-color); color: white; }
.message-content p { margin: 0; line-height: 1.5; white-space: pre-line; }

/* Quick Questions & Input */
.quick-questions { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; display: flex; gap: 10px; flex-wrap: wrap; background: #fff; }
.quick-btn { background: #f8f9fa; border: 1px solid #e9ecef; padding: 8px 15px; border-radius: 20px; font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; color: var(--text-color); }
.quick-btn:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }
.chat-input-container { padding: 20px; background: #fff; }
.chat-input { display: flex; gap: 15px; }
.chat-input input { flex: 1; padding: 15px 20px; border: 1px solid #e0e0e0; border-radius: 25px; font-size: 1rem; outline: none; transition: border-color 0.3s; }
.chat-input input:focus { border-color: var(--primary-color); }
.chat-input button { background: var(--primary-color); color: white; border: none; border-radius: 50%; width: 50px; height: 50px; cursor: pointer; font-size: 1.1rem; transition: transform 0.2s; display: flex; align-items: center; justify-content: center; }
.chat-input button:hover { transform: scale(1.1); }

/* Responsive */
@media (max-width: 768px) {
    .ai-stats-grid, .ai-tools-grid { grid-template-columns: 1fr; }
    .quick-questions { justify-content: center; }
    .message-content { max-width: 100%; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// --- LÓGICA DEL CHAT IA REAL ---

function sendDashboardMessage() {
    const input = document.getElementById('dashboardAiInput');
    const message = input.value.trim();
    // Obtenemos el CSRF token del layout
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    if (message) {
        // 1. Mostrar mensaje del usuario
        addDashboardMessage(message, 'user');
        input.value = '';
        
        // 2. Mostrar indicador de carga
        const loadingId = 'loading-' + Date.now();
        addDashboardMessage('<i class="fas fa-spinner fa-spin"></i> Analizando...', 'ai', loadingId);
        
        // 3. Llamar a la API REAL (AIController)
        fetch("{{ route('ai.chat.process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Eliminar indicador de carga
            const loader = document.getElementById(loadingId);
            if(loader) loader.remove();
            
            // Mostrar respuesta real de Gemini
            if (data.response) {
                addDashboardMessage(data.response, 'ai');
            } else {
                addDashboardMessage("Lo siento, no pude procesar tu solicitud en este momento.", 'ai');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const loader = document.getElementById(loadingId);
            if(loader) loader.remove();
            addDashboardMessage("Error de conexión con el servidor de IA.", 'ai');
        });
    }
}

function askQuickQuestion(question) {
    // Poner la pregunta en el input y enviarla automáticamente
    const input = document.getElementById('dashboardAiInput');
    input.value = question;
    sendDashboardMessage();
}

function addDashboardMessage(content, type, id = null) {
    const chat = document.getElementById('dashboardChatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    if(id) messageDiv.id = id;
    
    // Icono según quien habla
    const icon = type === 'user' ? 'fas fa-user' : 'fas fa-robot';
    
    messageDiv.innerHTML = `
        <div class="message-avatar">
            <i class="${icon}"></i>
        </div>
        <div class="message-content">
            <p>${content}</p>
        </div>
    `;
    
    chat.appendChild(messageDiv);
    chat.scrollTop = chat.scrollHeight;
}

// Permitir Enter en el input
document.getElementById('dashboardAiInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendDashboardMessage();
    }
});


// --- FUNCIONES DE HERRAMIENTAS (MODALES) ---
// Estas siguen siendo simulaciones visuales útiles para el usuario

function showQuickAnalysis() {
    Swal.fire({
        title: '🔍 Análisis Rápido IA',
        html: `
            <div style="text-align: left">
                <p><strong><i class="fas fa-check text-success"></i> Sistema:</strong> Operativo</p>
                <p><strong><i class="fas fa-server text-primary"></i> Datos:</strong> Sincronizados</p>
                <hr>
                <p>La IA ha detectado <strong>3 patrones nuevos</strong> en las visitas de esta semana. Revisa el reporte detallado.</p>
            </div>
        `,
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#e74c3c'
    });
}

function analyzeRisk() {
    Swal.fire({
        title: 'Analizando Riesgos...',
        html: 'Procesando historial médico y reportes de visitas...',
        timer: 1500,
        timerProgressBar: true,
        didOpen: () => { Swal.showLoading() }
    }).then(() => {
        // Aquí podrías redirigir a un reporte real o mostrar el resultado
        // Por ahora mostramos que la IA "pensó"
        askQuickQuestion("Genera un análisis de riesgo de los beneficiarios actuales");
    });
}

function generateReport() {
    // Usamos la función del chat para pedir el reporte a la IA
    askQuickQuestion("Genera un reporte ejecutivo de las actividades de este mes");
}

function predictNeeds() {
    askQuickQuestion("Predice qué insumos necesitaremos para el próximo mes basado en el historial");
}

function optimizeRoutes() {
    askQuickQuestion("¿Cuál es la ruta más eficiente para visitar a los adultos mayores en Wanchaq hoy?");
}
</script>
@endpush