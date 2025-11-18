<?php
/**
 * Modelo de Denuncia
 * Capa de Datos
 * Desarrollado por: Milenka Segundo Arteaga
 * Año: 2025
 */

require_once __DIR__ . '/../config/database.php';

class Denuncia {
    private $conn;
    private $table_name = "denuncias";

    public $id;
    public $titulo;
    public $descripcion;
    public $ubicacion;
    public $estado;
    public $ciudadano;
    public $telefono_ciudadano;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear una nueva denuncia
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (titulo, descripcion, ubicacion, estado, ciudadano, telefono_ciudadano, fecha_registro) 
                  VALUES (:titulo, :descripcion, :ubicacion, :estado, :ciudadano, :telefono_ciudadano, NOW())";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->ciudadano = htmlspecialchars(strip_tags($this->ciudadano));
        $this->telefono_ciudadano = htmlspecialchars(strip_tags($this->telefono_ciudadano));

        // Bind de parámetros
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":ciudadano", $this->ciudadano);
        $stmt->bindParam(":telefono_ciudadano", $this->telefono_ciudadano);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Leer todas las denuncias con paginación y búsqueda
     */
    public function leer($page = 1, $per_page = 10, $search = "") {
        $offset = ($page - 1) * $per_page;
        
        $where = "";
        if(!empty($search)) {
            $search_term = "%" . $search . "%";
            $where = "WHERE titulo LIKE :search OR ciudadano LIKE :search OR ubicacion LIKE :search";
        }

        $query = "SELECT id, titulo, descripcion, ubicacion, estado, ciudadano, telefono_ciudadano, 
                         DATE_FORMAT(fecha_registro, '%Y-%m-%d') as fecha
                  FROM " . $this->table_name . " 
                  " . $where . "
                  ORDER BY fecha_registro DESC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        
        if(!empty($search)) {
            $stmt->bindParam(":search", $search_term);
        }
        
        $stmt->bindParam(":limit", $per_page, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar total de denuncias con búsqueda
     */
    public function contar($search = "") {
        $where = "";
        if(!empty($search)) {
            $search_term = "%" . $search . "%";
            $where = "WHERE titulo LIKE :search OR ciudadano LIKE :search OR ubicacion LIKE :search";
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " " . $where;
        $stmt = $this->conn->prepare($query);
        
        if(!empty($search)) {
            $stmt->bindParam(":search", $search_term);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Leer una denuncia por ID
     */
    public function leerUno() {
        $query = "SELECT id, titulo, descripcion, ubicacion, estado, ciudadano, telefono_ciudadano, 
                         DATE_FORMAT(fecha_registro, '%Y-%m-%d') as fecha
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->titulo = $row['titulo'];
            $this->descripcion = $row['descripcion'];
            $this->ubicacion = $row['ubicacion'];
            $this->estado = $row['estado'];
            $this->ciudadano = $row['ciudadano'];
            $this->telefono_ciudadano = $row['telefono_ciudadano'];
            $this->fecha_registro = $row['fecha'];
            return true;
        }
        return false;
    }

    /**
     * Actualizar una denuncia
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                  SET titulo = :titulo, 
                      descripcion = :descripcion, 
                      ubicacion = :ubicacion, 
                      estado = :estado, 
                      ciudadano = :ciudadano, 
                      telefono_ciudadano = :telefono_ciudadano
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->ciudadano = htmlspecialchars(strip_tags($this->ciudadano));
        $this->telefono_ciudadano = htmlspecialchars(strip_tags($this->telefono_ciudadano));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind de parámetros
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":ciudadano", $this->ciudadano);
        $stmt->bindParam(":telefono_ciudadano", $this->telefono_ciudadano);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Eliminar una denuncia
     */
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
