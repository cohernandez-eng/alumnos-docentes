<?php
$host = "localhost";
$user = "root"; // usuario 
$pass = "";
$dbname = "escuela";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}
?>
