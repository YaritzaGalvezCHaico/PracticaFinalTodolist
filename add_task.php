<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "usuario", "contraseña", "tareas_db");

if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['descripcion'])) {
    $descripcion = $mysqli->real_escape_string($_POST['descripcion']);
    $usuario_id = $_SESSION['usuario_id']; // Asegúrate de que el usuario esté autenticado y su ID esté en la sesión

    // Insertar la nueva tarea
    $sql = "INSERT INTO tareas (descripcion, estado, usuario_id, creado_en) VALUES ('$descripcion', 'pendiente', $usuario_id, NOW())";
    
    if ($mysqli->query($sql) === TRUE) {
        echo '<div class="task-comment">Tarea añadida con éxito</div>';
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

$mysqli->close();
?>
