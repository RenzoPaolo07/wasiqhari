<?php
// iot-receiver.php
header('Content-Type: application/json');

// 🔍 Log para depuración (crea un archivo de log)
function logError($msg) {
    file_put_contents(__DIR__ . '/iot-errors.txt', date('Y-m-d H:i:s') . ' - ' . $msg . "\n", FILE_APPEND);
}

// 📌 1. Obtener datos del ESP32
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    logError('No se recibieron datos');
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos']);
    exit;
}

// 📌 2. Conectar a la base de datos (usa las credenciales correctas)
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=u797658412_wasiqhari',
        'u797658412_admin',
        'SharaBrianRenzo833'  // ← CAMBIA ESTO POR TU CONTRASEÑA REAL
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    logError('Error de conexión a BD: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error de base de datos',
        'details' => $e->getMessage()  // Esto te ayudará a depurar
    ]);
    exit;
}

// 📌 3. Buscar el paciente
try {
    $stmt = $pdo->prepare("SELECT id, nombres, apellidos FROM adultos_mayores WHERE dni = ?");
    $stmt->execute([$data['paciente_id']]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$paciente) {
        logError('Paciente no encontrado: ' . $data['paciente_id']);
        echo json_encode([
            'success' => false,
            'error' => 'Paciente no encontrado',
            'dni' => $data['paciente_id']
        ]);
        exit;
    }

    // 📌 4. Guardar en activity_logs
    $descripcion = json_encode([
        'paciente_id' => $paciente['id'],
        'paciente_nombre' => $paciente['nombres'] . ' ' . $paciente['apellidos'],
        'datos' => $data,
        'tipo_alerta' => $data['tipo_alerta'] ?? 'lectura_sensores',
        'fuerza_g' => $data['fuerza_g'] ?? 0,
        'fuente' => 'ESP32',
        'timestamp' => date('Y-m-d H:i:s')
    ]);

    $stmt = $pdo->prepare(
        "INSERT INTO activity_logs (adulto_mayor_id, accion, modulo, descripcion, created_at) 
         VALUES (?, 'LECTURA_SENSOR', 'IoT', ?, NOW())"
    );
    $stmt->execute([$paciente['id'], $descripcion]);

    echo json_encode([
        'success' => true,
        'message' => 'Datos guardados correctamente',
        'paciente' => $paciente['nombres'] . ' ' . $paciente['apellidos']
    ]);

} catch (PDOException $e) {
    logError('Error en consulta: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error en la consulta',
        'details' => $e->getMessage()
    ]);
}