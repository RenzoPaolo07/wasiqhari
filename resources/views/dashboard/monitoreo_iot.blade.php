@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2>Monitoreo IoT en tiempo real</h2>
    <div id="alertas-container" class="mt-4">
        <div class="alert alert-info">Esperando datos de sensores...</div>
    </div>
    
    <script>
        // Polling cada 5 segundos para simular WebSocket
        setInterval(() => {
            fetch('/api/iot/alertas-recientes')
                .then(res => res.json())
                .then(data => {
                    if(data.emergencias.length > 0) {
                        mostrarAlertas(data.emergencias);
                    }
                });
        }, 5000);
        
        function mostrarAlertas(emergencias) {
            const container = document.getElementById('alertas-container');
            container.innerHTML = emergencias.map(emerg => `
                <div class="alert alert-danger">
                    🔴 EMERGENCIA: ${emerg.motivo}<br>
                    Paciente: ${emerg.paciente}<br>
                    Hora: ${emerg.hora}
                </div>
            `).join('');
        }
    </script>
</div>
@endsection