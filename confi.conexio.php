<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "prototype_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error en la conexión: " . $conn->connect_error]));
}
