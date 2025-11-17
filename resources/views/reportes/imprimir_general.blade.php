<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte WasiQhari</title>
    <style>
        body { font-family: sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #e74c3c; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .resumen { display: flex; justify-content: space-between; margin-bottom: 20px; background: #f9f9f9; padding: 10px; border-radius: 5px; }
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
        <h1>WasiQhari - Reporte General</h1>
        <p>Fecha de emisión: {{ $fecha }}</p>
    </div>

    <div class="resumen">
        <div><strong>Total Adultos Mayores:</strong> {{ $total_adultos }}</div>
        <div><strong>Casos Críticos:</strong> {{ $criticos }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>DNI</th>
                <th>Edad</th>
                <th>Distrito</th>
                <th>Riesgo</th>
                <th>Salud</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adultos as $adulto)
            <tr>
                <td>{{ $adulto->nombres }} {{ $adulto->apellidos }}</td>
                <td>{{ $adulto->dni ?? '-' }}</td>
                <td>{{ $adulto->edad }}</td>
                <td>{{ $adulto->distrito }}</td>
                <td>{{ $adulto->nivel_riesgo }}</td>
                <td>{{ $adulto->estado_salud }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Opcional: Abrir diálogo de impresión automáticamente al cargar
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>