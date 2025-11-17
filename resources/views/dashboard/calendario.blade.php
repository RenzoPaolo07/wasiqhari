@extends('layouts.dashboard')

@section('title', 'Calendario de Actividades')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Calendario de Actividades</h1>
            <p>Planificación visual de todas las visitas y emergencias.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('visitas') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> Ver Lista
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<div id="modalEvento" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="eventTitle">Detalles de la Visita</h3>
            <span class="close" onclick="cerrarModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="event-details">
                <p><strong>📅 Fecha y Hora:</strong> <span id="eventDate"></span></p>
                <p><strong>👴 Adulto Mayor:</strong> <span id="eventAdulto"></span></p>
                <p><strong>🤝 Voluntario:</strong> <span id="eventVoluntario"></span></p>
                <p><strong>🏷️ Tipo:</strong> <span id="eventTipo"></span></p>
                <p><strong>🚨 Emergencia:</strong> <span id="eventEmergencia"></span></p>
                <p><strong>📝 Observaciones:</strong> <span id="eventObs"></span></p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="cerrarModal()">Cerrar</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        min-height: 700px; /* Altura del calendario */
    }
    
    /* Personalización de colores del calendario */
    .fc-toolbar-title { font-size: 1.5rem !important; color: var(--dark-color); }
    .fc-button-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; }
    .fc-event { cursor: pointer; border: none; }
    
    /* Modal Styles (reutilizando tus estilos) */
    .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(5px); }
    .modal-content { background-color: white; margin: 10% auto; padding: 0; border-radius: 15px; width: 90%; max-width: 500px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: slideIn 0.3s ease; }
    .modal-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; border-radius: 15px 15px 0 0; }
    .modal-body { padding: 20px; }
    .modal-footer { padding: 15px 20px; text-align: right; border-top: 1px solid #eee; }
    .event-details p { margin-bottom: 10px; font-size: 0.95rem; color: #2c3e50; }
    .event-details strong { color: #5a6a7b; width: 120px; display: inline-block; }
    
    @keyframes slideIn { from {transform: translateY(-20px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
</style>
@endpush

@push('scripts')
<script>
    const modal = document.getElementById('modalEvento');

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es', // Idioma español
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            events: "{{ route('api.calendario') }}", // Carga los eventos desde tu ruta
            
            // Al hacer clic en un evento
            eventClick: function(info) {
                const props = info.event.extendedProps;
                
                // Llenar el modal
                document.getElementById('eventTitle').innerText = info.event.title;
                document.getElementById('eventDate').innerText = info.event.start.toLocaleString();
                document.getElementById('eventAdulto').innerText = props.adulto;
                document.getElementById('eventVoluntario').innerText = props.voluntario;
                document.getElementById('eventTipo').innerText = props.tipo;
                
                const emergenciaSpan = document.getElementById('eventEmergencia');
                emergenciaSpan.innerText = props.emergencia;
                emergenciaSpan.style.color = props.emergencia === 'SI' ? 'red' : 'green';
                emergenciaSpan.style.fontWeight = 'bold';
                
                document.getElementById('eventObs').innerText = props.observaciones;
                
                // Mostrar modal
                modal.style.display = 'block';
            }
        });
        calendar.render();
    });

    function cerrarModal() {
        modal.style.display = 'none';
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
            cerrarModal();
        }
    }
</script>
@endpush