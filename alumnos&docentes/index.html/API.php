<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$user = "root";  // usuario 
$pass = "";      // contraseña vacía 
$dbname = "escuela";

// Conexión a MySQL
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

$entidad = $_GET['entidad'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

if (!$entidad) {
    echo json_encode(["error" => "Entidad no especificada"]);
    exit;
}

// ----- LISTAR -----
if ($method === 'GET') {
    $sql = "SELECT * FROM $entidad";
    $result = $conn->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}

// ----- CREAR -----
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($entidad === 'alumnos') {
        $nombre = $conn->real_escape_string($data['nombre']);
        $edad = (int)$data['edad'];
        $sql = "INSERT INTO alumnos (nombre, edad) VALUES ('$nombre', $edad)";
    } elseif ($entidad === 'docentes') {
        $nombre = $conn->real_escape_string($data['nombre']);
        $materia = $conn->real_escape_string($data['materia']);
        $sql = "INSERT INTO docentes (nombre, materia) VALUES ('$nombre', '$materia')";
    }
    $conn->query($sql);
    echo json_encode(["success" => $conn->affected_rows > 0]);
}

// ----- ACTUALIZAR -----
elseif ($method === 'PUT') {
    parse_str($_SERVER['QUERY_STRING'], $query);
    $id = $query['id'] ?? null;

    if (!$id) {
        echo json_encode(["error" => "ID requerido para actualizar"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if ($entidad === 'alumnos') {
        $nombre = $conn->real_escape_string($data['nombre']);
        $edad = (int)$data['edad'];
        $sql = "UPDATE alumnos SET nombre='$nombre', edad=$edad WHERE id=$id";
    } elseif ($entidad === 'docentes') {
        $nombre = $conn->real_escape_string($data['nombre']);
        $materia = $conn->real_escape_string($data['materia']);
        $sql = "UPDATE docentes SET nombre='$nombre', materia='$materia' WHERE id=$id";
    }
    $conn->query($sql);
    echo json_encode(["success" => $conn->affected_rows > 0]);
}

// ----- ELIMINAR -----
elseif ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(["error" => "ID requerido para eliminar"]);
        exit;
    }

    $sql = "DELETE FROM $entidad WHERE id=$id";
    $conn->query($sql);
    echo json_encode(["success" => $conn->affected_rows > 0]);
}

$conn->close();
?>
