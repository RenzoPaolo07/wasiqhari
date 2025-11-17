<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Visitas - WasiQhari</title>
    <style>
        body { font-family: sans-serif; padding: 20px; color: #333; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #e74c3c; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; color: #2c3e50; }
        
        .badge { padding: 3px 6px; border-radius: 4px; color: white; font-size: 10px; font-weight: bold; }
        .badge-si { background-color: #e74c3c; }
        .badge-no { background-color: #27ae60; }
        
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; }
        }
        .btn-print {
            background: #e74c3c; color: white; border: none; padding: 10px 20px; 
            border-radius: 5px; cursor: pointer; font-size: 14px; margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print no-print">🖨️ Imprimir / Guardar como PDF</button>

    <div class="header">
        <h1>WasiQhari - Historial de Visitas</h1>
        <p>Fecha de emisión: {{ $fecha }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha / Hora</th>
                <th>Adulto Mayor</th>
                <th>Voluntario</th>
                <th>Tipo de Visita</th>
                <th>Estado Emocional</th>
                <th>Emergencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitas as $visita)
            <tr>
                <td>
                    {{ $visita->fecha_visita->format('d/m/Y') }}<br>
                    <small>{{ $visita->fecha_visita->format('H:i') }}</small>
                </td>
                <td>{{ $visita->adultoMayor->nombres }} {{ $visita->adultoMayor->apellidos }}</td>
                <td>{{ $visita->voluntario->user->name }}</td>
                <td>{{ $visita->tipo_visita }}</td>
                <td>{{ $visita->estado_emocional }}</td>
                <td style="text-align: center;">
                    @if($visita->emergencia)
                        <span class="badge badge-si">SÍ</span>
                    @else
                        <span class="badge badge-no">NO</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>