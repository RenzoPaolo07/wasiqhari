<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Voluntarios - WasiQhari</title>
    <style>
        body { font-family: sans-serif; padding: 20px; color: #333; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #e74c3c; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; color: #2c3e50; }
        
        @media print {
            .no-print { display: none; }
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
        <h1>WasiQhari - Equipo de Voluntarios</h1>
        <p>Fecha de emisión: {{ $fecha }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Correo Electrónico</th>
                <th>Teléfono</th>
                <th>Distrito</th>
                <th>Estado</th>
                <th>Disponibilidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($voluntarios as $voluntario)
            <tr>
                <td>{{ $voluntario->user->name }}</td>
                <td>{{ $voluntario->user->email }}</td>
                <td>{{ $voluntario->telefono ?? '-' }}</td>
                <td>{{ $voluntario->distrito ?? '-' }}</td>
                <td>{{ $voluntario->estado }}</td>
                <td>{{ $voluntario->disponibilidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>