<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "El script PHP está funcionando.<br>";

// Prueba de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tareas_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión a la base de datos exitosa.<br>";

$conn->close();
?>
