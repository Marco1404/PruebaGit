<?php
$host = 'localhost';  // o el nombre del servidor si usas otro
$user = 'root';  // tu usuario de MySQL
$pass = '';  // tu contraseña de MySQL
$dbname = 'tienda';  // el nombre de la base de datos que creaste

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
