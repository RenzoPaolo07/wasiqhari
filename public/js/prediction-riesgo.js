// Sistema de predicción de riesgo para adultos mayores
class PredictorRiesgo {
    constructor() {
        this.factoresRiesgo = {
            edad: { peso: 0.3, max: 100 },
            estadoSalud: { peso: 0.25, valores: { 'Bueno': 1, 'Regular': 3, 'Malo': 5, 'Critico': 8 } },
            apoyoFamiliar: { peso: 0.2, valores: { 'Ocasional': 2, 'Poco': 5, 'Ninguno': 8 } },
            tiempoSinVisita: { peso: 0.15, max: 30 },
            enfermedadesCronicas: { peso: 0.1, max: 5 }
        };
    }

    calcularRiesgo(adultoMayor) {
        let puntajeTotal = 0;
        let factores = [];

        // Edad
        const riesgoEdad = (adultoMayor.edad / this.factoresRiesgo.edad.max) * this.factoresRiesgo.edad.peso * 10;
        factores.push({ nombre: 'Edad', puntaje: riesgoEdad });

        // Estado de salud
        const riesgoSalud = this.factoresRiesgo.estadoSalud.valores[adultoMayor.estado_salud] * this.factoresRiesgo.estadoSalud.peso;
        factores.push({ nombre: 'Estado de Salud', puntaje: riesgoSalud });

        // Apoyo familiar
        const riesgoApoyo = this.factoresRiesgo.apoyoFamiliar.valores[adultoMayor.apoyo_familiar] * this.factoresRiesgo.apoyoFamiliar.peso;
        factores.push({ nombre: 'Apoyo Familiar', puntaje: riesgoApoyo });

        // Tiempo sin visita (simulado)
        const diasSinVisita = Math.min(adultoMayor.dias_sin_visita || 7, this.factoresRiesgo.tiempoSinVisita.max);
        const riesgoVisita = (diasSinVisita / this.factoresRiesgo.tiempoSinVisita.max) * this.factoresRiesgo.tiempoSinVisita.peso * 10;
        factores.push({ nombre: 'Tiempo sin Visita', puntaje: riesgoVisita });

        // Enfermedades crónicas (simulado basado en necesidades)
        const numEnfermedades = this.contarEnfermedades(adultoMayor.necesidades);
        const riesgoEnfermedades = (numEnfermedades / this.factoresRiesgo.enfermedadesCronicas.max) * this.factoresRiesgo.enfermedadesCronicas.peso * 10;
        factores.push({ nombre: 'Enfermedades Crónicas', puntaje: riesgoEnfermedades });

        // Calcular puntaje total
        puntajeTotal = factores.reduce((total, factor) => total + factor.puntaje, 0);

        return {
            puntaje: Math.min(puntajeTotal, 10),
            nivel: this.obtenerNivelRiesgo(puntajeTotal),
            factores: factores.sort((a, b) => b.puntaje - a.puntaje)
        };
    }

    contarEnfermedades(necesidades) {
        if (!necesidades) return 1;
        
        const enfermedades = [
            'diabetes', 'artritis', 'hipertensión', 'cardíaco', 'renal', 
            'respiratorio', 'demencia', 'alzheimer', 'parkinson', 'cáncer'
        ];
        
        return enfermedades.filter(enfermedad => 
            necesidades.toLowerCase().includes(enfermedad)
        ).length || 1;
    }

    obtenerNivelRiesgo(puntaje) {
        if (puntaje >= 8) return { nivel: 'Alto', color: '#e74c3c', icono: '🔴' };
        if (puntaje >= 5) return { nivel: 'Medio', color: '#f39c12', icono: '🟡' };
        return { nivel: 'Bajo', color: '#27ae60', icono: '🟢' };
    }

    generarRecomendaciones(analisisRiesgo) {
        const recomendaciones = [];
        const { puntaje, nivel, factores } = analisisRiesgo;

        if (nivel.nivel === 'Alto') {
            recomendaciones.push('🚨 INTERVENCIÓN INMEDIATA REQUERIDA');
            recomendaciones.push('• Contactar servicios de emergencia si es necesario');
            recomendaciones.push('• Asignar voluntario para visita urgente');
            recomendaciones.push('• Notificar a familiares y autoridades locales');
        }

        if (puntaje >= 6) {
            recomendaciones.push('📞 Contacto prioritario en las próximas 48 horas');
        }

        // Recomendaciones específicas por factores de riesgo
        factores.slice(0, 2).forEach(factor => {
            switch(factor.nombre) {
                case 'Estado de Salud':
                    recomendaciones.push('🏥 Evaluación médica prioritaria');
                    break;
                case 'Apoyo Familiar':
                    recomendaciones.push('👥 Buscar red de apoyo alternativa');
                    break;
                case 'Tiempo sin Visita':
                    recomendaciones.push('📅 Programar visita de seguimiento inmediata');
                    break;
            }
        });

        if (recomendaciones.length === 0) {
            recomendaciones.push('✅ Situación estable, mantener monitoreo regular');
            recomendaciones.push('• Continuar con visitas programadas');
            recomendaciones.push('• Reforzar red de apoyo social');
        }

        return recomendaciones;
    }
}

// Uso del predictor
const predictor = new PredictorRiesgo();

// Función para analizar todos los adultos mayores
function analizarRiesgoGeneral() {
    // Obtener datos de adultos mayores (simulado)
    const adultosMayores = JSON.parse(localStorage.getItem('adultos_mayores')) || [];
    
    const analisisGeneral = adultosMayores.map(adulto => {
        const analisis = predictor.calcularRiesgo(adulto);
        return {
            ...adulto,
            riesgo: analisis
        };
    });

    // Ordenar por nivel de riesgo
    return analisisGeneral.sort((a, b) => b.riesgo.puntaje - a.riesgo.puntaje);
}

// Integración con el dashboard
function mostrarPanelPrediccion() {
    const analisis = analizarRiesgoGeneral();
    
    Swal.fire({
        title: '🤖 Análisis Predictivo de Riesgo',
        html: `
            <div class="prediccion-panel">
                <div class="resumen-riesgo">
                    <h4>Resumen General</h4>
                    <div class="metricas-riesgo">
                        <div class="metrica">
                            <span class="valor">${analisis.filter(a => a.riesgo.nivel.nivel === 'Alto').length}</span>
                            <span class="label">Alto Riesgo</span>
                        </div>
                        <div class="metrica">
                            <span class="valor">${analisis.filter(a => a.riesgo.nivel.nivel === 'Medio').length}</span>
                            <span class="label">Riesgo Medio</span>
                        </div>
                        <div class="metrica">
                            <span class="valor">${analisis.filter(a => a.riesgo.nivel.nivel === 'Bajo').length}</span>
                            <span class="label">Bajo Riesgo</span>
                        </div>
                    </div>
                </div>
                
                <div class="casos-criticos">
                    <h4>Casos que Requieren Atención Inmediata</h4>
                    ${analisis.slice(0, 3).map(adulto => `
                        <div class="caso-critico">
                            <div class="caso-header">
                                <span class="riesgo-indicador" style="color: ${adulto.riesgo.nivel.color}">
                                    ${adulto.riesgo.nivel.icono}
                                </span>
                                <strong>${adulto.nombres} ${adulto.apellidos}</strong>
                                <span class="puntaje-riesgo">${adulto.riesgo.puntaje.toFixed(1)}/10</span>
                            </div>
                            <div class="factores-principales">
                                ${adulto.riesgo.factores.slice(0, 2).map(f => 
                                    `<span class="factor">${f.nombre}: ${f.puntaje.toFixed(1)}</span>`
                                ).join('')}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `,
        width: 600,
        confirmButtonText: 'Ver Reporte Completo',
        showCancelButton: true,
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            generarReportePrediccion(analisis);
        }
    });
}

function generarReportePrediccion(analisis) {
    // Generar y descargar reporte PDF (simulado)
    Swal.fire({
        title: '📊 Generando Reporte',
        html: `
            <div class="reporte-prediccion">
                <p>Análisis predictivo generado para ${analisis.length} adultos mayores</p>
                <div class="estadisticas-rapidas">
                    <p><strong>Puntaje promedio:</strong> ${(analisis.reduce((sum, a) => sum + a.riesgo.puntaje, 0) / analisis.length).toFixed(2)}/10</p>
                    <p><strong>Casos de alto riesgo:</strong> ${analisis.filter(a => a.riesgo.nivel.nivel === 'Alto').length}</p>
                    <p><strong>Factor más común:</strong> ${analisis[0].riesgo.factores[0].nombre}</p>
                </div>
            </div>
        `,
        icon: 'success',
        confirmButtonText: 'Descargar PDF'
    });
}

// Integrar con el sistema existente
document.addEventListener('DOMContentLoaded', function() {
    // Agregar botón de predicción al dashboard
    const headerActions = document.querySelector('.dashboard-header .header-actions');
    if (headerActions) {
        const botonPrediccion = document.createElement('button');
        botonPrediccion.className = 'btn btn-warning';
        botonPrediccion.innerHTML = '<i class="fas fa-brain"></i> Análisis Predictivo';
        botonPrediccion.onclick = mostrarPanelPrediccion;
        headerActions.appendChild(botonPrediccion);
    }
});