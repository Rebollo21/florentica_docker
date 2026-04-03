<?php
// BOLLOTECH CLOUD BRIDGE V2.5 - FLORENTICA FINAL
header('Content-Type: application/json');

// Reporte de errores para diagnóstico de operaciones
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- CONFIGURACIÓN DE BASE DE DATOS ---
$db_host = "sql207.infinityfree.com"; 
$db_user = "if0_41446731";           
$db_pass = "idtgUIH1HT";  
$db_name = "if0_41446731_florentica"; 

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "Fallo SQL: " . $conn->connect_error]));
}

// Captura del flujo JSON
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data && isset($data['temperature']) && isset($data['humidity'])) {
    
    $temp = mysqli_real_escape_string($conn, $data['temperature']);
    $hum  = mysqli_real_escape_string($conn, $data['humidity']);
    $device = "ESP32_WOKWI_01";

    $sql = "INSERT INTO sensor_readings (temperature, humidity, device_id) VALUES ('$temp', '$hum', '$device')";

    if ($conn->query($sql) === TRUE) {
        // Forzamos el 201 para confirmar creación en la DB
        http_response_code(201); 
        echo json_encode(["status" => "success", "db_id" => $conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Esperando datos validos..."]);
}

$conn->close();
?>