<?php
// Iniciar sesión para acceder al ID del usuario
session_start();

// Asegúrate de que el ID del usuario esté guardado en la sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redirige a login si no está autenticado
    exit();
}

$usuario_id = $_SESSION['usuario_id']; // Obtén el ID del usuario

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "tareas_db");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Comprobar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $busqueda = $conexion->real_escape_string($_POST['busqueda']); // Escapar caracteres especiales para evitar inyecciones SQL

    // Búsqueda en la tabla de tareas
    $query_tareas = "SELECT * FROM tareas WHERE descripcion LIKE '%$busqueda%' AND usuario_id = $usuario_id";
    $result_tareas = $conexion->query($query_tareas);

    // Búsqueda en la tabla de notas
    $query_notas = "SELECT * FROM notas WHERE contenido LIKE '%$busqueda%' AND usuario_id = $usuario_id";
    $result_notas = $conexion->query($query_notas);

    // Búsqueda en la tabla de categorías
    $query_categorias = "SELECT * FROM categorias WHERE nombre LIKE '%$busqueda%' AND usuario_id = $usuario_id";
    $result_categorias = $conexion->query($query_categorias);

    // Mostrar resultados
    echo "<h2>Resultados de la búsqueda para '$busqueda':</h2>";

    // Mostrar tareas
    if ($result_tareas->num_rows > 0) {
        echo "<h3>Tareas:</h3><ul>";
        while ($tarea = $result_tareas->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($tarea['descripcion']) . " (Estado: " . htmlspecialchars($tarea['estado']) . ", Prioridad: " . htmlspecialchars($tarea['prioridad']) . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron tareas.</p>";
    }

    // Mostrar notas
    if ($result_notas->num_rows > 0) {
        echo "<h3>Notas:</h3><ul>";
        while ($nota = $result_notas->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($nota['contenido']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron notas.</p>";
    }

    // Mostrar categorías
    if ($result_categorias->num_rows > 0) {
        echo "<h3>Categorías:</h3><ul>";
        while ($categoria = $result_categorias->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($categoria['nombre']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron categorías.</p>";
    }
}
?>

<!-- HTML para el formulario de búsqueda -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de tener un archivo CSS si deseas estilos -->
</head>
<body>
    <h1>Búsqueda en tu lista</h1>
    <form action="buscar.php" method="POST">
        <input type="text" name="busqueda" placeholder="Buscar..." required>
        <button type="submit">Buscar</button>
    </form>
    <a href="tareas.php">Volver a la lista de tareas</a> <!-- Enlace para regresar -->
</body>
</html>
