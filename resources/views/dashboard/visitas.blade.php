<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Registro de Visitas</h1>
            <p>Gestiona y monitorea las visitas realizadas</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="abrirModalNuevaVisita()">
                <i class="fas fa-plus"></i> Nueva Visita
            </button>
            <button class="btn btn-secondary" onclick="generarReporteVisitas()">
                <i class="fas fa-download"></i> Exportar
            </button>
        </div>
    </div>

    <div class="filtros-section">
        <div class="filtros-grid">
            <div class="form-group">
                <label for="filtroFechaDesde">Fecha Desde</label>
                <input type="date" id="filtroFechaDesde" onchange="filtrarVisitas()">
            </div>
            
            <div class="form-group">
                <label for="filtroFechaHasta">Fecha Hasta</label>
                <input type="date" id="filtroFechaHasta" onchange="filtrarVisitas()">
            </div>
            
                        <div class="form-group">
                <label for="filtroVoluntario">Voluntario</label>
                <select id="filtroVoluntario" onchange="filtrarVisitas()">
                    <option value="">Todos los voluntarios</option>
                    @foreach($voluntarios as $voluntario)
                    <option value="{{ $voluntario->id }}">Voluntario {{ $voluntario->id }} ({{ $voluntario->distrito ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="filtroTipo">Tipo de Visita</label>
                <select id="filtroTipo" onchange="filtrarVisitas()">
                    <option value="">Todos los tipos</option>
                    <option value="Acompañamiento">Acompañamiento</option>
                    <option value="Entrega de alimentos">Entrega de alimentos</option>
                    <option value="Atención médica">Atención médica</option>
                    <option value="Apoyo emocional">Apoyo emocional</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Adulto Mayor</th>
                        <th>Voluntario</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaVisitas">
                    <?php foreach($data['visitas'] as $visita): 
                        // Preparación de variables para PHP/HTML más limpio
                        $isEmergency = $visita['emergencia'];
                        $rowClass = $isEmergency ? 'fila-emergencia' : '';
                        $dateFormatted = date('d/m/Y', strtotime($visita['fecha_visita']));
                        $timeFormatted = date('H:i', strtotime($visita['fecha_visita']));
                        $tipoClase = strtolower(str_replace(' ', '-', $visita['tipo_visita']));
                        $observacionesTruncadas = strlen($visita['observaciones']) > 50 ? substr($visita['observaciones'], 0, 50) . '...' : $visita['observaciones'];
                    ?>
                    <tr class="<?= $rowClass; ?>">
                        <td>
                            <div class="fecha-visita">
                                <strong data-tooltip="Fecha de la Visita"><?= $dateFormatted; ?></strong>
                                <small data-tooltip="Hora de la Visita"><?= $timeFormatted; ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">👵</div>
                                <div class="user-details">
                                    <strong>{{ $visita->adulto->nombres ?? 'N/A' }} {{ $visita->adulto->apellidos ?? '' }}</strong>
                                    <small>{{ $visita->adulto->distrito ?? 'Cusco' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="voluntario-info">
                                <i class="fas fa-user"></i>
                                <span>{{ $visita->voluntario->user->name ?? 'Voluntario ' . $visita->voluntario_id }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="tipo-visita tipo-<?= $tipoClase; ?>">
                                <?= $visita['tipo_visita']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="estado-visita">
                                <span class="estado-emocional estado-<?= strtolower($visita['estado_emocional']); ?>">
                                    <?= $visita['estado_emocional']; ?>
                                </span>
                                <span class="estado-fisico fisico-<?= strtolower($visita['estado_fisico']); ?>">
                                    <?= $visita['estado_fisico']; ?>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="observaciones-truncadas" data-tooltip="<?= htmlentities($visita['observaciones']); ?>">
                                <?= $observacionesTruncadas; ?>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" onclick="verDetalleVisita(<?= $visita['id']; ?>)" data-tooltip="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon btn-edit" onclick="editarVisita(<?= $visita['id']; ?>)" data-tooltip="Editar Visita">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if($isEmergency): ?>
                                <button class="btn-icon btn-emergency" onclick="gestionarEmergencia(<?= $visita['id']; ?>)" data-tooltip="Gestionar Emergencia">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="visitaModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Registrar Nueva Visita</h3>
            <span class="close" onclick="cerrarModalVisita()">&times;</span>
        </div>
        <form id="visitaForm">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="visita_adulto">Adulto Mayor *</label>
                        <select id="visita_adulto" name="adulto_id" required>
                            <option value="">Seleccionar adulto mayor</option>
                            @foreach($adultos as $adulto)
                            <option value="{{ $adulto->id }}">
                                {{ $adulto->nombres }} {{ $adulto->apellidos }} - {{ $adulto->distrito }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="visita_voluntario">Voluntario *</label>
                        <select id="visita_voluntario" name="voluntario_id" required>
                            <option value="">Seleccionar voluntario</option>
                            @foreach($voluntarios as $voluntario)
                            <option value="{{ $voluntario->id }}">
                                {{ $voluntario->name }} - {{ $voluntario->distrito ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="visita_fecha">Fecha y Hora *</label>
                        <input type="datetime-local" id="visita_fecha" name="fecha_visita" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="visita_tipo">Tipo de Visita *</label>
                        <select id="visita_tipo" name="tipo_visita" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="Acompañamiento">Acompañamiento</option>
                            <option value="Entrega de alimentos">Entrega de alimentos</option>
                            <option value="Atención médica">Atención médica</option>
                            <option value="Apoyo emocional">Apoyo emocional</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="visita_emocional">Estado Emocional</label>
                        <select id="visita_emocional" name="estado_emocional">
                            <option value="Estable">Estable</option>
                            <option value="Triste">Triste</option>
                            <option value="Ansioso">Ansioso</option>
                            <option value="Eufórico">Eufórico</option>
                            <option value="Deprimido">Deprimido</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="visita_fisico">Estado Físico</label>
                        <select id="visita_fisico" name="estado_fisico">
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Malo">Malo</option>
                            <option value="Crítico">Crítico</option>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="visita_observaciones">Observaciones *</label>
                        <textarea id="visita_observaciones" name="observaciones" required 
                                 placeholder="Describa los detalles de la visita, comportamiento del adulto mayor, condiciones del lugar, etc." 
                                 rows="4"></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="visita_necesidades">Necesidades Detectadas</label>
                        <textarea id="visita_necesidades" name="necesidades_detectadas" 
                                 placeholder="Liste las necesidades identificadas durante la visita..."
                                 rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" id="visita_emergencia" name="emergencia" value="1">
                            <span class="checkmark"></span>
                            Marcar como Emergencia
                        </label>
                    </div>
                    
                    <div class="form-group full-width" id="camposEmergencia" style="display: none;">
                        <label for="visita_descripcion_emergencia">Descripción de la Emergencia</label>
                        <textarea id="visita_descripcion_emergencia" name="descripcion_emergencia" 
                                 placeholder="Describa detalladamente la situación de emergencia..."
                                 rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalVisita()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Visita</button>
            </div>
        </form>
    </div>
</div>

<script>
// Mejoras: Mover toda la lógica de eventos aquí
document.addEventListener('DOMContentLoaded', () => {
    // Inicialización de Modal
    const visitaModal = document.getElementById('visitaModal');
    const emergenciaCheckbox = document.getElementById('visita_emergencia');
    const camposEmergencia = document.getElementById('camposEmergencia');

    // Función para cerrar modal (más limpia)
    window.cerrarModalVisita = () => {
        visitaModal.style.display = 'none';
        document.getElementById('visitaForm').reset(); // Opcional: limpiar al cerrar
    }

    // Cerrar modal al hacer click fuera
    window.onclick = (event) => { 
        if (event.target === visitaModal) {
            cerrarModalVisita();
        }
    }

    // Toggle campos de emergencia
    emergenciaCheckbox.addEventListener('change', function() {
        camposEmergencia.style.display = this.checked ? 'block' : 'none';
    });

    // Envío del formulario de visita (simulación de registro)
    document.getElementById('visitaForm').onsubmit = (e) => {
        e.preventDefault();
        
        // Simulación de envío AJAX
        console.log('Datos del formulario enviados...');

        Swal.fire({
            title: '¡Visita Registrada!',
            text: 'La visita ha sido registrada exitosamente en el sistema.',
            icon: 'success',
            timer: 2000
        });
        
        cerrarModalVisita();
    };

    // Cerrar modal con el botón X
    document.querySelector('#visitaModal .close').onclick = cerrarModalVisita;

    // Abrir Modal y establecer fecha (función global)
    window.abrirModalNuevaVisita = () => {
        visitaModal.style.display = 'block';
        const now = new Date();
        // Ajuste para el formato datetime-local
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('visita_fecha').value = now.toISOString().slice(0, 16);
    }

});

// Funciones globales (mejor mantenerlas así si se llaman desde onclick en el HTML)
function verDetalleVisita(id) {
    // **NOTA:** En una implementación real, aquí harías una llamada AJAX para obtener los datos.
    // Los datos aquí son Hardcodeados/Mockeados.
    Swal.fire({
        title: 'Detalle de Visita #'+id,
        html: `
            <div class="detalle-visita">
                <div class="detalle-header">
                    <h4>Visita #${id}</h4>
                    <span class="badge badge-warning">Completada</span>
                </div>
                <div class="detalle-info">
                    <div class="info-item">
                        <strong>Adulto Mayor:</strong> Martina Quispe
                    </div>
                    <div class="info-item">
                        <strong>Voluntario:</strong> Juan Pérez
                    </div>
                    <div class="info-item full-width">
                        <strong>Observaciones:</strong>
                        <p>El adulto mayor se encontraba en buen estado de ánimo, conversamos sobre sus necesidades básicas. Se observó que necesita medicamentos para la artritis.</p>
                    </div>
                    <div class="info-item">
                        <strong>Necesidades Detectadas:</strong>
                        <ul>
                            <li>Medicamentos para artritis</li>
                            <li>Alimentos no perecibles</li>
                            <li>Abrigo adicional</li>
                        </ul>
                    </div>
                </div>
            </div>
        `,
        width: 600,
        confirmButtonText: 'Cerrar'
    });
}

function editarVisita(id) {
    Swal.fire({
        title: 'Editar Visita',
        text: 'Abriendo modal de edición para la visita '+id+'...',
        icon: 'info'
    });
    // Aquí iría la lógica para cargar los datos en el modal de registro y cambiar el título/botón.
}

function gestionarEmergencia(id) {
    Swal.fire({
        title: '🚨 Gestión de Emergencia',
        html: `
            <div class="emergencia-form">
                <div class="alert alert-danger">
                    <strong>¡ATENCIÓN!</strong> Esta visita fue marcada como emergencia.
                </div>
                <div class="form-group">
                    <label>Acción a tomar</label>
                    <select id="accionEmergencia" class="swal2-input">
                        <option value="">Seleccionar acción...</option>
                        <option value="contactar_familia">Contactar familiares</option>
                        <option value="derivar_salud">Derivar a centro de salud</option>
                        <option value="visita_urgencia">Programar visita de urgencia</option>
                        <option value="otro">Otra acción</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Prioridad</label>
                    <select id="prioridadEmergencia" class="swal2-input">
                        <option value="alta">Alta</option>
                        <option value="media">Media</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Comentarios adicionales</label>
                    <textarea id="comentariosEmergencia" class="swal2-textarea" placeholder="Describa las acciones tomadas..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Registrar Acción',
        preConfirm: () => {
            return {
                accion: document.getElementById('accionEmergencia').value,
                prioridad: document.getElementById('prioridadEmergencia').value,
                comentarios: document.getElementById('comentariosEmergencia').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                '¡Acción Registrada!',
                `La acción '${result.value.accion}' con prioridad ${result.value.prioridad} ha sido registrada.`,
                'success'
            );
        }
    });
}

function filtrarVisitas() {
    // Simular llamada AJAX al controlador
    const fechaDesde = document.getElementById('filtroFechaDesde').value;
    const fechaHasta = document.getElementById('filtroFechaHasta').value;
    const voluntario = document.getElementById('filtroVoluntario').value;
    const tipo = document.getElementById('filtroTipo').value;
    
    console.log('Filtrando visitas...', { fechaDesde, fechaHasta, voluntario, tipo });
    // Aquí iría la lógica AJAX que recarga el contenido de #tablaVisitas
}

function generarReporteVisitas() {
    Swal.fire({
        title: 'Generando Reporte',
        text: 'El reporte se está generando...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        Swal.fire(
            '¡Reporte Listo!',
            'El reporte de visitas ha sido generado y descargado.',
            'success'
        );
    });
}
</script>

<style>
/* ** Nota: Los estilos CSS están en gran medida bien y no necesitan cambios funcionales importantes.
** Se mantienen para que tengas el código completo y coherente.
** El único cambio visual es la implementación del data-tooltip en la tabla.
*/

/* ESTILOS GENERALES Y LAYOUT */
.dashboard-container {
    padding: 20px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.dashboard-header h1 {
    margin: 0 0 5px 0;
}

.header-actions .btn {
    margin-left: 10px;
}

/* Filtros */
.filtros-section {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}

.filtros-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

/* Tablas */
.table-container {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    overflow-x: auto; /* Importante para el responsive de tablas */
}

.table-responsive {
    overflow-x: auto;
    border-radius: 10px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.data-table th, .data-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    text-align: left;
    white-space: nowrap;
    vertical-align: middle;
}

.data-table th {
    background: #f7f9fc;
    font-weight: 600;
    color: var(--dark-color);
    text-transform: uppercase;
    font-size: 0.8rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: modalSlideDown 0.3s ease;
}

.modal-content.large {
    max-width: 800px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.modal-header h3 {
    margin: 0;
    color: var(--primary-color);
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #f0f0f0;
    text-align: right;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.modal-body {
    padding: 20px;
}

@keyframes modalSlideDown {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ESTILOS ESPECÍFICOS DE LA TABLA Y ESTADOS */
.fila-emergencia {
    background: #fff5f5 !important;
    border-left: 4px solid #e74c3c;
    animation: pulseEmergency 2s infinite;
}

@keyframes pulseEmergency {
    0%, 100% { background-color: #fff5f5; }
    50% { background-color: #ffe6e6; }
}

.fecha-visita {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 90px;
}

.fecha-visita strong {
    display: block;
    font-size: 0.9rem;
    color: var(--dark-color);
    margin-bottom: 2px;
}

.fecha-visita small {
    color: var(--text-light);
    font-size: 0.8rem;
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 10px;
}

.voluntario-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 0;
}

.voluntario-info i {
    color: var(--primary-color);
    font-size: 1.1rem;
}

.voluntario-info span {
    font-weight: 500;
    color: var(--dark-color);
}

.tipo-visita {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    text-align: center;
    min-width: 120px;
}

/* Colores para Tipos de Visita */
.tipo-acompañamiento { background: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; }
.tipo-entrega-de-alimentos { background: #e8f5e8; color: #2e7d32; border: 1px solid #c8e6c9; }
.tipo-atención-médica { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
.tipo-apoyo-emocional { background: #f3e5f5; color: #7b1fa2; border: 1px solid #e1bee7; }
.tipo-otro { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }

.estado-visita {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 140px;
}

.estado-emocional, .estado-fisico {
    padding: 4px 8px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    text-align: center;
    text-transform: uppercase;
}

/* Colores para Estado Emocional */
.estado-estable { background: #e8f5e8; color: #2e7d32; border: 1px solid #c8e6c9; }
.estado-triste { background: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; }
.estado-ansioso { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
.estado-eufórico { background: #f3e5f5; color: #7b1fa2; border: 1px solid #e1bee7; }
.estado-deprimido { background: #f5f5f5; color: #616161; border: 1px solid #e0e0e0; }

/* Colores para Estado Físico */
.fisico-bueno { background: #e8f5e8; color: #2e7d32; border: 1px solid #c8e6c9; }
.fisico-regular { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
.fisico-malo { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
.fisico-crítico { background: #fce4ec; color: #ad1457; border: 1px solid #f8bbd9; }

.observaciones-truncadas {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-color);
    font-size: 0.9rem;
    line-height: 1.4;
}

.action-buttons {
    display: flex;
    gap: 5px;
    align-items: center;
}

.btn-icon {
    border: 1px solid #ccc;
    background: #f8f8f8;
    color: #666;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-icon:hover {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.btn-view { border-color: #b3e5fc; color: #0288d1; }
.btn-edit { border-color: #fff176; color: #f9a825; }

.btn-emergency {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
    transition: all 0.3s ease;
}

.btn-emergency:hover {
    background: #ffcdd2;
    transform: scale(1.1);
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    75% { transform: translateX(2px); }
}

/* Modal Detalle (SweetAlert) */
.detalle-visita { text-align: left; max-height: 60vh; overflow-y: auto; }
.detalle-header { 
    display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; 
    padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; 
    position: sticky; top: 0; background: white; z-index: 1;
}
.info-item { margin: 12px 0; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
.info-item:last-child { border-bottom: none; }
.info-item.full-width { grid-column: 1 / -1; }
.info-item strong { color: var(--dark-color); display: block; margin-bottom: 8px; font-size: 0.95rem; }
.info-item p, .info-item ul { margin: 8px 0 0 0; color: var(--text-color); line-height: 1.5; }
.info-item ul { padding-left: 20px; }

/* Otros */
.alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid; }
.alert-danger { background: #ffebee; color: #c62828; border-left-color: #c62828; border: 1px solid #ffcdd2; }
.badge { padding: 6px 12px; border-radius: 15px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
.badge-warning { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }


/* ESTILOS DEL FORMULARIO */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    max-height: 60vh;
    overflow-y: auto;
    padding-right: 10px;
}
.form-group.full-width {
    grid-column: 1 / -1;
}

/* Mejoras visuales para campos del formulario */
.form-group input, .form-group select, .form-group textarea {
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
    box-sizing: border-box;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: var(--primary-color, #3498db);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

#camposEmergencia {
    background: #fff5f5;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #ffcdd2;
    margin-top: 10px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; max-height: 0; }
    to { opacity: 1; max-height: 200px; }
}

/* Tooltips (se mueve del tr:hover) */
[data-tooltip] {
    position: relative;
    cursor: help;
}

[data-tooltip]:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 5px;
}

[data-tooltip]:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: #333;
    z-index: 1000;
    margin-bottom: -5px;
}

/* MEDIA QUERIES (se mantienen para responsive) */
@media (max-width: 1200px) {
    .tipo-visita { min-width: 100px; font-size: 0.7rem; padding: 4px 8px; }
    .estado-visita { min-width: 120px; }
}

@media (max-width: 768px) {
    .dashboard-header { flex-direction: column; align-items: flex-start; }
    .header-actions { margin-top: 15px; }
    .fecha-visita { min-width: 70px; }
    .fecha-visita strong { font-size: 0.8rem; }
    .fecha-visita small { font-size: 0.7rem; }
    .tipo-visita { min-width: 80px; font-size: 0.65rem; padding: 3px 6px; }
    .estado-visita { min-width: 100px; gap: 3px; }
    .estado-emocional, .estado-fisico { font-size: 0.65rem; padding: 3px 6px; }
    .observaciones-truncadas { max-width: 150px; font-size: 0.8rem; }
    .voluntario-info { flex-direction: column; gap: 2px; text-align: center; }
    .voluntario-info span { font-size: 0.8rem; }
    .form-grid { grid-template-columns: 1fr; }
}

@media (max-width: 576px) {
    .data-table { font-size: 0.8rem; }
    .data-table th, .data-table td { padding: 8px 10px; }
    .observaciones-truncadas { max-width: 120px; }
    .action-buttons { flex-direction: column; gap: 3px; }
    .btn-icon { width: 30px; height: 30px; font-size: 0.8rem; }
}
</style>