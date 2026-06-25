<?php
// iot-receiver.php
header('Content-Type: application/json');

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

// 📌 2. Conectar a la base de datos
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=u797658412_wasiqhari',
        'u797658412_admin',
        'SharaBrianRenzo833'  // ← Cambia por tu contraseña real
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    logError('Error de conexión a BD: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error de base de datos',
        'details' => $e->getMessage()
    ]);
    exit;
}

// 📌 3. Buscar el ID del usuario ESP32
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(['esp32@wasiqhari.top']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        logError('Usuario ESP32 no encontrado');
        echo json_encode(['success' => false, 'error' => 'Usuario ESP32 no encontrado']);
        exit;
    }

    $userId = $user['id'];

} catch (PDOException $e) {
    logError('Error buscando usuario: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error en la consulta', 'details' => $e->getMessage()]);
    exit;
}

// 📌 4. Buscar el paciente por DNI
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

    // 📌 5. Guardar en activity_logs
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
        "INSERT INTO activity_logs (user_id, accion, modulo, descripcion, created_at) 
         VALUES (?, 'LECTURA_SENSOR', 'IoT', ?, NOW())"
    );
    $stmt->execute([$userId, $descripcion]);

    echo json_encode([
        'success' => true,
        'message' => 'Datos guardados correctamente',
        'paciente' => $paciente['nombres'] . ' ' . $paciente['apellidos'],
        'data' => $data
    ]);

} catch (PDOException $e) {
    logError('Error en consulta: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error en la consulta',
        'details' => $e->getMessage()
    ]);
}