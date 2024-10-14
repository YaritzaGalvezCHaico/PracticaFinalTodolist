<?php
// Conexión a la base de datos
include('conexion.php');

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $id = intval($_GET['id']);
    $estado = htmlspecialchars($_GET['estado']);

    // Consulta para actualizar el estado de la tarea
    $query = "UPDATE tareas SET estado = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('si', $estado, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>

