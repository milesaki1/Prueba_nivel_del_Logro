<?php
/**
 * Configuración de la base de datos
 * Desarrollado por: Milenka Segundo Arteaga
 * Año: 2025
 */

class Database {
    private $host = "localhost";
    private $db_name = "denuncias_municipio";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Intentar conectar directamente a la base de datos
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_TIMEOUT => 5
                )
            );
        } catch(PDOException $exception) {
            // Guardar el error en el log
            error_log("Error de conexión a la base de datos: " . $exception->getMessage());
            
            // Mensaje más amigable según el tipo de error
            $errorCode = $exception->getCode();
            $errorMessage = $exception->getMessage();
            
            if($errorCode == 1049) {
                // Base de datos no existe
                $errorMessage = "La base de datos '" . $this->db_name . "' no existe. Por favor, ejecuta el script SQL: database/denuncias.sql";
            } elseif($errorCode == 1045) {
                // Acceso denegado
                $errorMessage = "Acceso denegado. Verifica el usuario y contraseña en config/database.php";
            } elseif($errorCode == 2002) {
                // No se puede conectar al servidor
                $errorMessage = "No se puede conectar al servidor MySQL. Verifica que MySQL esté corriendo en XAMPP";
            }
            
            // Lanzar excepción para que el controlador la maneje
            throw new Exception($errorMessage);
        }

        return $this->conn;
    }
}
