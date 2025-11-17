<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Credencial - {{ $adulto->nombres }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f0f0f0; display: flex; flex-direction: column; align-items: center; padding: 20px; }
        .credencial-container {
            width: 350px;
            height: 550px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            overflow: hidden;
            position: relative;
            text-align: center;
            border: 1px solid #ddd;
        }
        .header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            height: 120px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .header h2 { margin: 0; font-size: 24px; letter-spacing: 1px; }
        .header span { font-size: 12px; opacity: 0.9; text-transform: uppercase; letter-spacing: 2px; }
        
        .photo-container {
            width: 120px;
            height: 120px;
            background: #fff;
            border-radius: 50%;
            margin: -60px auto 15px;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .photo-container img { width: 100%; height: 100%; object-fit: cover; }
        .photo-placeholder { font-size: 50px; color: #ccc; }

        .info h1 { margin: 0; color: #2c3e50; font-size: 22px; }
        .info p { margin: 5px 0; color: #7f8c8d; font-size: 14px; }
        .role-badge {
            background: #e6ffe6; color: #27ae60;
            padding: 5px 15px; border-radius: 20px;
            font-weight: bold; font-size: 12px; text-transform: uppercase;
            display: inline-block; margin: 10px 0;
        }

        .qr-code { margin: 20px auto; border: 2px solid #f0f0f0; padding: 5px; border-radius: 10px; display: inline-block; }
        
        .footer {
            position: absolute; bottom: 0; width: 100%;
            background: #f8f9fa; padding: 15px 0;
            font-size: 11px; color: #95a5a6; border-top: 1px solid #eee;
        }

        .btn-print {
            margin-bottom: 20px; padding: 10px 20px; background: #2c3e50; color: white;
            border: none; border-radius: 5px; cursor: pointer; font-size: 16px;
        }
        @media print { .btn-print { display: none; } body { background: white; } .credencial-container { box-shadow: none; border: 1px solid #000; } }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print">🖨️ Imprimir Credencial</button>

    <div class="credencial-container">
        <div class="header">
            <h2>WasiQhari</h2>
            <span>Red de Apoyo Social</span>
        </div>
        
        <div class="photo-container">
            <div class="photo-placeholder">👤</div>
        </div>

        <div class="info">
            <h1>{{ $adulto->nombres }}</h1>
            <h1>{{ $adulto->apellidos }}</h1>
            <p>DNI: {{ $adulto->dni ?? 'S/D' }}</p>
            <span class="role-badge">Beneficiario</span>
            <p><strong>Distrito:</strong> {{ $adulto->distrito }}</p>
        </div>

        <div class="qr-code">
            <img src="{{ $qrCode }}" alt="QR Code" width="100">
        </div>

        <div class="footer">
            Válido para identificación en el programa WasiQhari.<br>
            ID: {{ str_pad($adulto->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    </div>
</body>
</html>