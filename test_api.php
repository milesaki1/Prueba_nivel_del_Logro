<?php
/**
 * Script de prueba simple de la API
 * Desarrollado por: Milenka Segundo Arteaga
 * Año: 2025
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=UTF-8');

echo "<h2>Prueba de API</h2>";
echo "<hr>";

// Probar la conexión directamente
try {
    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/controllers/DenunciaController.php';
    
    echo "<h3 style='color: green;'>✓ Archivos cargados correctamente</h3>";
    
    // Intentar crear el controlador
    $controller = new DenunciaController();
    echo "<h3 style='color: green;'>✓ Controlador creado correctamente</h3>";
    
    // Probar una consulta simple
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET['page'] = 1;
    $_GET['per_page'] = 5;
    
    echo "<h3>Probando endpoint GET...</h3>";
    ob_start();
    $controller->procesarSolicitud();
    $output = ob_get_clean();
    
    echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>";
    echo htmlspecialchars($output);
    echo "</pre>";
    
    // Verificar si es JSON válido
    $json = json_decode($output);
    if($json !== null) {
        echo "<h3 style='color: green;'>✓ Respuesta JSON válida</h3>";
        if(isset($json->success) && $json->success) {
            echo "<h3 style='color: green;'>✓ API funcionando correctamente</h3>";
        } else {
            echo "<h3 style='color: orange;'>⚠ API responde pero con error:</h3>";
            echo "<p>" . ($json->mensaje ?? $json->error ?? 'Error desconocido') . "</p>";
        }
    } else {
        echo "<h3 style='color: red;'>✗ La respuesta no es JSON válido</h3>";
    }
    
} catch(Exception $e) {
    echo "<h3 style='color: red;'>✗ Error:</h3>";
    echo "<p style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #fee; padding: 15px; border-radius: 5px;'>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}

echo "<hr>";
echo "<h3>Prueba directa de la API:</h3>";
echo "<p>Abre esta URL en tu navegador o usa Postman:</p>";
echo "<code>http://localhost/Prueba_nivel_del_Logro/api/denuncias.php?page=1&per_page=5</code>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
        background-color: #f5f5f5;
    }
    h2 {
        color: #1e3a8a;
    }
    code {
        background-color: #e5e7eb;
        padding: 5px 10px;
        border-radius: 3px;
        display: inline-block;
        margin: 10px 0;
    }
</style>

