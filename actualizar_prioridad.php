<?php
// Conexión a la base de datos
include('conexion.php');

if (isset($_GET['id']) && isset($_GET['prioridad'])) {
    $id = intval($_GET['id']);
    $estado = htmlspecialchars($_GET['prioridad']);

    // Consulta para actualizar el estado de la tarea
    $query = "UPDATE tareas SET prioridad= ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('si', $prioridad, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
