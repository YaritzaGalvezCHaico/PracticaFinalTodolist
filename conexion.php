<?php
// Datos de conexión
$host = "localhost"; // Cambia según tu configuración
$user = "usuario"; // Cambia por tu usuario
$password = "contraseña"; // Cambia por tu contraseña
$dbname = "tareas_db"; // Cambia por tu nombre de base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

