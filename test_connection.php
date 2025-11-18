<?php
/**
 * Script de prueba de conexión a la base de datos
 * Desarrollado por: Milenka Segundo Arteaga
 * Año: 2025
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "<h2>Prueba de Conexión a la Base de Datos</h2>";
echo "<hr>";

// Información de configuración
echo "<h3>Configuración:</h3>";
echo "<ul>";
echo "<li><strong>Host:</strong> localhost</li>";
echo "<li><strong>Base de datos:</strong> denuncias_municipio</li>";
echo "<li><strong>Usuario:</strong> root</li>";
echo "<li><strong>Contraseña:</strong> " . (empty($password) ? "(vacía)" : "***") . "</li>";
echo "</ul>";
echo "<hr>";

// Prueba 1: Verificar extensión PDO
echo "<h3>Prueba 1: Extensión PDO</h3>";
if(extension_loaded('pdo') && extension_loaded('pdo_mysql')) {
    echo "<p style='color: green;'>✓ PDO y PDO_MySQL están instalados</p>";
} else {
    echo "<p style='color: red;'>✗ PDO o PDO_MySQL no están instalados</p>";
    echo "<p>Necesitas instalar la extensión PDO de MySQL en PHP</p>";
}
echo "<hr>";

// Prueba 2: Conectar sin especificar base de datos
echo "<h3>Prueba 2: Conexión a MySQL</h3>";
try {
    $conn = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Conexión a MySQL exitosa</p>";
    
    // Prueba 3: Verificar si la base de datos existe
    echo "<h3>Prueba 3: Verificar Base de Datos</h3>";
    $stmt = $conn->query("SHOW DATABASES LIKE 'denuncias_municipio'");
    if($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ La base de datos 'denuncias_municipio' existe</p>";
        
        // Prueba 4: Conectar a la base de datos específica
        echo "<h3>Prueba 4: Conexión a la Base de Datos Específica</h3>";
        try {
            $db = new Database();
            $connection = $db->getConnection();
            
            if($connection !== null) {
                echo "<p style='color: green;'>✓ Conexión a 'denuncias_municipio' exitosa</p>";
                
                // Prueba 5: Verificar tabla
                echo "<h3>Prueba 5: Verificar Tabla 'denuncias'</h3>";
                $stmt = $connection->query("SHOW TABLES LIKE 'denuncias'");
                if($stmt->rowCount() > 0) {
                    echo "<p style='color: green;'>✓ La tabla 'denuncias' existe</p>";
                    
                    // Contar registros
                    $stmt = $connection->query("SELECT COUNT(*) as total FROM denuncias");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<p><strong>Registros en la tabla:</strong> " . $result['total'] . "</p>";
                } else {
                    echo "<p style='color: orange;'>⚠ La tabla 'denuncias' no existe</p>";
                    echo "<p>Necesitas ejecutar el script SQL: database/denuncias.sql</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ No se pudo conectar a la base de datos</p>";
            }
        } catch(Exception $e) {
            echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠ La base de datos 'denuncias_municipio' NO existe</p>";
        echo "<p><strong>Solución:</strong> Ejecuta el script SQL: <code>database/denuncias.sql</code> en phpMyAdmin</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error de conexión: " . $e->getMessage() . "</p>";
    echo "<p><strong>Posibles causas:</strong></p>";
    echo "<ul>";
    echo "<li>MySQL no está corriendo</li>";
    echo "<li>Usuario o contraseña incorrectos</li>";
    echo "<li>Puerto de MySQL no es el estándar (3306)</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h3>Resumen</h3>";
echo "<p>Si todas las pruebas muestran ✓ (verde), tu configuración está correcta.</p>";
echo "<p>Si ves ⚠ (naranja) o ✗ (rojo), sigue las instrucciones mostradas arriba.</p>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #f5f5f5;
    }
    h2 {
        color: #1e3a8a;
    }
    h3 {
        color: #3b82f6;
        margin-top: 20px;
    }
    code {
        background-color: #e5e7eb;
        padding: 2px 6px;
        border-radius: 3px;
    }
</style>

