<?php  
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$mensaje_tipo = ''; // Nuevo: Tipo de mensaje (success, confirm)
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'id'; // Orden por defecto

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "tareas_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);  

if ($conn->connect_error) {  
    die("Conexión fallida: " . $conn->connect_error);  
}

// Verificar si el nombre del usuario está disponible en la sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Consultar el nombre del usuario
    $sql_usuario = "SELECT nombre_usuario FROM usuarios WHERE id = $usuario_id";
    $result_usuario = $conn->query($sql_usuario);

    if ($result_usuario->num_rows > 0) {
        $usuario = $result_usuario->fetch_assoc();
        // Guardar el nombre del usuario en la sesión
        $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];
    } else {
        die("Error: El usuario no existe.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nueva_tarea']) && !empty($_POST['descripcion'])) {
        $descripcion = $conn->real_escape_string($_POST['descripcion']);
        
        // Verificar si la tarea ya existe
        $sql_verificar_tarea = "SELECT id FROM tareas WHERE descripcion = '$descripcion' AND usuario_id = $usuario_id";
        $result_verificar_tarea = $conn->query($sql_verificar_tarea);

        if ($result_verificar_tarea->num_rows > 0) {
            $mensaje = 'La tarea con esta descripción ya existe. ¿Desea volver a crearla?';
            $mensaje_tipo = 'confirm'; // Tipo de mensaje: confirm
            $_SESSION['nueva_tarea_descripcion'] = $descripcion; // Guardar la descripción en la sesión para volver a usarla
        } else {
            $sql_max_id = "SELECT IFNULL(MAX(id), 0) AS max_id FROM tareas WHERE usuario_id = $usuario_id";
            $result_max_id = $conn->query($sql_max_id);
            $max_id = $result_max_id->fetch_assoc()['max_id'];
            $next_id = $max_id + 1;

            $sql_tarea = "INSERT INTO tareas (id, descripcion, estado, usuario_id) VALUES ($next_id, '$descripcion', 'pendiente', $usuario_id)";
            
            if ($conn->query($sql_tarea) === TRUE) {
                $mensaje = 'Tarea creada correctamente.';
                $mensaje_tipo = 'success'; // Tipo de mensaje: success
            } else {
                $mensaje = "Error: " . $sql_tarea . "<br>" . $conn->error;
                $mensaje_tipo = 'error'; // Tipo de mensaje: error
            }
        }
    } elseif (isset($_POST['confirmar_creacion'])) {
        // Confirmar la creación de la tarea duplicada
        $descripcion = $_SESSION['nueva_tarea_descripcion'];
        $sql_max_id = "SELECT IFNULL(MAX(id), 0) AS max_id FROM tareas WHERE usuario_id = $usuario_id";
        $result_max_id = $conn->query($sql_max_id);
        $max_id = $result_max_id->fetch_assoc()['max_id'];
        $next_id = $max_id + 1;

        $sql_tarea = "INSERT INTO tareas (id, descripcion, estado, usuario_id) VALUES ($next_id, '$descripcion', 'pendiente', $usuario_id)";
        
        if ($conn->query($sql_tarea) === TRUE) {
            $mensaje = 'Tarea creada correctamente.';
            $mensaje_tipo = 'success'; // Tipo de mensaje: success
            unset($_SESSION['nueva_tarea_descripcion']); // Limpiar la descripción de la sesión
        } else {
            $mensaje = "Error: " . $sql_tarea . "<br>" . $conn->error;
            $mensaje_tipo = 'error'; // Tipo de mensaje: error
        }
    } elseif (isset($_POST['cambiar_estado'])) {
        $tarea_id = intval($_POST['tarea_id']);
        $estado_actual = $_POST['estado_actual'];
        $nuevo_estado = ($estado_actual == 'pendiente') ? 'completada' : 'pendiente';

        $sql_cambiar_estado = "UPDATE tareas SET estado = '$nuevo_estado' WHERE id = $tarea_id AND usuario_id = $usuario_id";
        
        if ($conn->query($sql_cambiar_estado) === TRUE) {
            header("Location: tareas.php");
            exit();
        } else {
            $mensaje = "Error: " . $sql_cambiar_estado . "<br>" . $conn->error;
            $mensaje_tipo = 'error'; // Tipo de mensaje: error
        }
    } elseif (isset($_POST['eliminar_tarea'])) {
        $tarea_id = intval($_POST['tarea_id']);
        $sql_eliminar_tarea = "DELETE FROM tareas WHERE id = $tarea_id AND usuario_id = $usuario_id";
        
        if ($conn->query($sql_eliminar_tarea) === TRUE) {
            header("Location: tareas.php");
            exit();
        } else {
            $mensaje = "Error: " . $sql_eliminar_tarea . "<br>" . $conn->error;
            $mensaje_tipo = 'error'; // Tipo de mensaje: error
        }
    }
}

// Consulta sin paginación
$sql_tareas = "SELECT id, descripcion, estado FROM tareas WHERE usuario_id = $usuario_id ORDER BY $orden";
$result_tareas = $conn->query($sql_tareas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Tareas <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></title>
    <link rel="stylesheet" href="tareas.css">
</head>
<body id="tareas-page">
    <div class="tareas-container">
        <h2>Listado de Tareas de <span id="nombre-usuario"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span></h2>

        <!-- Formulario de búsqueda -->
        <form class="search-form" action="tareas.php" method="GET">
            <input type="text" name="busqueda" placeholder="Buscar tarea...">
            <button type="submit">Buscar</button>
        </form>

        <!-- Menú de ordenación -->
        <form class="sort-form" action="tareas.php" method="GET">
            <label for="orden">Ordenar por:</label>
            <select name="orden" id="orden" onchange="this.form.submit()">
                <option value="id" <?php if ($orden == 'id') echo 'selected'; ?>>ID</option>
                <option value="descripcion" <?php if ($orden == 'descripcion') echo 'selected'; ?>>Descripción</option>
                <option value="estado" <?php if ($orden == 'estado') echo 'selected'; ?>>Estado</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tarea</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_tareas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                        <td>
                            <form action="tareas.php" method="POST" style="display:inline;">
                                <input type="hidden" name="tarea_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="estado_actual" value="<?php echo htmlspecialchars($row['estado']); ?>">
                                <button type="submit" name="cambiar_estado" class="status <?php echo htmlspecialchars($row['estado']); ?>">
                                    <?php echo htmlspecialchars($row['estado']); ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="editar_tarea.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="tarea_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="edit-task">Editar</button>
                                </form>
                                <form action="tareas.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="tarea_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" name="eliminar_tarea" class="remove-task">Borrar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="bottom-actions">
            <form action="tareas.php" method="POST" class="add-task-form">
                <input type="text" name="descripcion" placeholder="Descripción de la tarea" required>
                <button type="submit" name="nueva_tarea" class="add-task">Añadir Tarea</button>
            </form>

            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo htmlspecialchars($mensaje_tipo); ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                    <?php if ($mensaje_tipo == 'confirm' && isset($_SESSION['nueva_tarea_descripcion'])): ?>
                        <!-- Formulario de confirmación -->
                        <form action="tareas.php" method="POST">
                            <button type="submit" name="confirmar_creacion" class="confirm-task">Confirmar</button>
                            <a href="tareas.php" class="cancel-task">Cancelar</a>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form action="logout.php" method="POST">
                <button type="submit" class="logout-button">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>

