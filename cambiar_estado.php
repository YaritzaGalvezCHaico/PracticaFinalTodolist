<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $tareaId = intval($_POST['id']);
    $nuevoEstado = 'completado'; // O puedes cambiarlo según tu lógica

    $query = "UPDATE tareas SET estado = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('si', $nuevoEstado, $tareaId);
    $stmt->execute();

    echo json_encode(['success' => true]); // Retorna un JSON
} else {
    echo json_encode(['success' => false]);
}
?>
