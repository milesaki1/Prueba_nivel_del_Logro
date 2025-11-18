<?php
/**
 * Controlador de Denuncia
 * Capa de Lógica de Negocio
 * Desarrollado por: Milenka Segundo Arteaga
 * Año: 2025
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Denuncia.php';

class DenunciaController {
    private $db;
    private $denuncia;

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->getConnection();
            
            if($this->db === null) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
            
            $this->denuncia = new Denuncia($this->db);
        } catch(Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(array(
                "success" => false,
                "mensaje" => $e->getMessage(),
                "error" => "Error de conexión a la base de datos. Verifica la configuración en config/database.php"
            ));
            exit();
        }
    }

    /**
     * Procesar la solicitud según el método HTTP
     */
    public function procesarSolicitud() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        header('Content-Type: application/json; charset=UTF-8');

        switch($method) {
            case 'GET':
                $this->obtenerDenuncias();
                break;
            case 'POST':
                $this->crearDenuncia();
                break;
            case 'PUT':
                $this->actualizarDenuncia();
                break;
            case 'DELETE':
                $this->eliminarDenuncia();
                break;
            default:
                http_response_code(405);
                echo json_encode(array("mensaje" => "Método no permitido"));
                break;
        }
    }

    /**
     * Obtener denuncias con paginación y búsqueda
     */
    private function obtenerDenuncias() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : "";

        if(isset($_GET['id'])) {
            // Obtener una denuncia específica
            $this->denuncia->id = (int)$_GET['id'];
            if($this->denuncia->leerUno()) {
                echo json_encode(array(
                    "success" => true,
                    "data" => array(
                        "id" => $this->denuncia->id,
                        "titulo" => $this->denuncia->titulo,
                        "descripcion" => $this->denuncia->descripcion,
                        "ubicacion" => $this->denuncia->ubicacion,
                        "estado" => $this->denuncia->estado,
                        "ciudadano" => $this->denuncia->ciudadano,
                        "telefono_ciudadano" => $this->denuncia->telefono_ciudadano,
                        "fecha" => $this->denuncia->fecha_registro
                    )
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("success" => false, "mensaje" => "Denuncia no encontrada"));
            }
        } else {
            // Obtener lista de denuncias
            $denuncias = $this->denuncia->leer($page, $per_page, $search);
            $total = $this->denuncia->contar($search);
            $total_pages = ceil($total / $per_page);

            echo json_encode(array(
                "success" => true,
                "data" => $denuncias,
                "pagination" => array(
                    "current_page" => $page,
                    "per_page" => $per_page,
                    "total" => $total,
                    "total_pages" => $total_pages
                )
            ));
        }
    }

    /**
     * Crear una nueva denuncia
     */
    private function crearDenuncia() {
        $raw_data = file_get_contents("php://input");
        $data = json_decode($raw_data);

        if(json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array("success" => false, "mensaje" => "JSON inválido"));
            return;
        }

        if(!empty($data->titulo) && !empty($data->descripcion) && !empty($data->ubicacion) && 
           !empty($data->estado) && !empty($data->ciudadano)) {
            
            $this->denuncia->titulo = $data->titulo;
            $this->denuncia->descripcion = $data->descripcion;
            $this->denuncia->ubicacion = $data->ubicacion;
            $this->denuncia->estado = $data->estado;
            $this->denuncia->ciudadano = $data->ciudadano;
            $this->denuncia->telefono_ciudadano = isset($data->telefono_ciudadano) ? $data->telefono_ciudadano : "";

            if($this->denuncia->crear()) {
                http_response_code(201);
                echo json_encode(array(
                    "success" => true,
                    "mensaje" => "Denuncia creada exitosamente",
                    "id" => $this->denuncia->id
                ));
            } else {
                http_response_code(503);
                echo json_encode(array("success" => false, "mensaje" => "No se pudo crear la denuncia"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("success" => false, "mensaje" => "Datos incompletos"));
        }
    }

    /**
     * Actualizar una denuncia existente
     */
    private function actualizarDenuncia() {
        $raw_data = file_get_contents("php://input");
        $data = json_decode($raw_data);

        if(json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array("success" => false, "mensaje" => "JSON inválido"));
            return;
        }

        if(!empty($data->id) && !empty($data->titulo) && !empty($data->descripcion) && 
           !empty($data->ubicacion) && !empty($data->estado) && !empty($data->ciudadano)) {
            
            $this->denuncia->id = $data->id;
            $this->denuncia->titulo = $data->titulo;
            $this->denuncia->descripcion = $data->descripcion;
            $this->denuncia->ubicacion = $data->ubicacion;
            $this->denuncia->estado = $data->estado;
            $this->denuncia->ciudadano = $data->ciudadano;
            $this->denuncia->telefono_ciudadano = isset($data->telefono_ciudadano) ? $data->telefono_ciudadano : "";

            if($this->denuncia->actualizar()) {
                echo json_encode(array("success" => true, "mensaje" => "Denuncia actualizada exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("success" => false, "mensaje" => "No se pudo actualizar la denuncia"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("success" => false, "mensaje" => "Datos incompletos"));
        }
    }

    /**
     * Eliminar una denuncia
     */
    private function eliminarDenuncia() {
        $raw_data = file_get_contents("php://input");
        $data = json_decode($raw_data);

        if(json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array("success" => false, "mensaje" => "JSON inválido"));
            return;
        }

        if(!empty($data->id)) {
            $this->denuncia->id = $data->id;

            if($this->denuncia->eliminar()) {
                echo json_encode(array("success" => true, "mensaje" => "Denuncia eliminada exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("success" => false, "mensaje" => "No se pudo eliminar la denuncia"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("success" => false, "mensaje" => "ID de denuncia requerido"));
        }
    }
}
