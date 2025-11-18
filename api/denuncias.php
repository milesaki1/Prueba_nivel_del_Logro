<?php
/**
 * API REST para Denuncias
 * Desarrollado por: Milenka Segundo Arteaga
 * Año: 2025
 */

// Configurar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar preflight requests
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Evitar cualquier salida antes del JSON
// Desactivar errores en pantalla para evitar que se mezclen con el JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Limpiar cualquier buffer previo y empezar uno nuevo
while(ob_get_level()) {
    ob_end_clean();
}

// Iniciar buffer de salida para capturar cualquier salida no deseada
ob_start();

try {
    require_once __DIR__ . '/../controllers/DenunciaController.php';
    
    $controller = new DenunciaController();
    $controller->procesarSolicitud();
    
    // Limpiar cualquier salida no deseada antes de enviar
    ob_end_flush();
} catch(Exception $e) {
    // Limpiar cualquier salida previa
    ob_end_clean();
    
    // Devolver error en formato JSON
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array(
        "success" => false,
        "mensaje" => $e->getMessage(),
        "error" => "Error en el servidor"
    ));
}
