<?php  
session_start(); // Inicia la sesión

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html"); // Redirige a la página de login si no hay sesión
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "tareas_db"; 

// Crear conexión  
$conn = new mysqli($servername, $username, $password, $dbname);  

// Verificar la conexión  
if ($conn->connect_error) {  
    die("Conexión fallida: " . $conn->connect_error);  
}

// Obtener el ID de la tarea desde la URL
$tarea_id = isset($_GET['tarea_id']) ? intval($_GET['tarea_id']) : 0;

// Obtener la información de la tarea para mostrarla en el formulario
$tarea = null;
if ($tarea_id > 0) {
    $sql_tarea = "SELECT id, descripcion FROM tareas WHERE id = $tarea_id AND usuario_id = $usuario_id";
    $result = $conn->query($sql_tarea);

    if ($result->num_rows > 0) {
        $tarea = $result->fetch_assoc();
    } else {
        echo "Tarea no encontrada o no tienes permisos para editarla.";
        exit();
    }
}

// Procesar el formulario para actualizar la tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_tarea'])) {
    $descripcion = $_POST['descripcion'];

    // Asegúrate de escapar los datos para evitar inyecciones SQL
    $descripcion = $conn->real_escape_string($descripcion);

    $sql_actualizar = "UPDATE tareas SET descripcion = '$descripcion' WHERE id = $tarea_id AND usuario_id = $usuario_id";
    
    if ($conn->query($sql_actualizar) === TRUE) {
        // Redirige a la página principal de tareas
        header("Location: tareas.php");
        exit();
    } else {
        echo "Error: " . $sql_actualizar . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="tareas.css">
</head>
<body id="editar-tarea-page">
    <div class="tareas-container">
        <h2>Editar Tarea</h2>
        <?php if ($tarea): ?>
            <form action="editar_tarea.php?tarea_id=<?php echo $tarea_id; ?>" method="POST">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($tarea['descripcion']); ?>" required>
                <button type="submit" name="actualizar_tarea" class="update-task">Actualizar Tarea</button>
            </form>
        <?php else: ?>
            <p>Tarea no encontrada o no tienes permisos para editarla.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
