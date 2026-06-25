<?php
// iot-receiver.php
header('Content-Type: application/json');

// Conectar a la base de datos de Laravel
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=u797658412_wasiqhari',
        'u797658412_admin',
        'SharaBrianRenzo833'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
    exit;
}

// Obtener datos del ESP32
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos']);
    exit;
}

// Buscar el paciente por DNI
$stmt = $pdo->prepare("SELECT id, nombres, apellidos FROM adultos_mayores WHERE dni = ?");
$stmt->execute([$data['paciente_id']]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    echo json_encode(['success' => false, 'error' => 'Paciente no encontrado']);
    exit;
}

// Guardar en activity_logs
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
    'paciente' => $paciente['nombres'] . ' ' . $paciente['apellidos'],
    'data' => $data
]);