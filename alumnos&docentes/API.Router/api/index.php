
<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../controllers/AlumnoController.php';
require_once '../controllers/DocenteController.php';

$entidad = $_GET['entidad'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

if (!$entidad) {
    echo json_encode(["error" => "Entidad no especificada"]);
    exit;
}

// Obtener ID si existe
$id = null;
if ($method === 'PUT' || $method === 'DELETE') {
    if ($method === 'PUT') {
        parse_str($_SERVER['QUERY_STRING'], $query);
        $id = $query['id'] ?? null;
    } else {
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'] ?? null;
    }

    if (!$id) {
        echo json_encode(["error" => "ID requerido"]);
        exit;
    }
}

// Instanciar controlador según la entidad
if ($entidad === 'alumnos') {
    $controller = new AlumnoController();
} elseif ($entidad === 'docentes') {
    $controller = new DocenteController();
} else {
    echo json_encode(["error" => "Entidad no válida"]);
    exit;
}

// Manejar la solicitud según el método HTTP
switch ($method) {
    case 'GET':
        $controller->index();
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->create($data);
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->update($id, $data);
        break;
        
    case 'DELETE':
        $controller->delete($id);
        break;
        
    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
