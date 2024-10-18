<?php
session_start();
include 'barra_menu.php'; // Incluir la barra de menú

// Conectar a la base de datos
$conn = new mysqli('localhost', 'usuario', 'contraseña', 'tareas_db');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener el ID de la categoría
function obtenerCategoriaID($nombre_categoria, $usuario_id) {
    $conn = new mysqli('localhost', 'usuario', 'contraseña', 'tareas_db');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $sql = "SELECT id FROM categorias WHERE nombre = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nombre_categoria, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($fila = $resultado->fetch_assoc()) {
        $categoria_id = $fila['id'];
    } else {
        $categoria_id = null; 
    }
    
    $stmt->close();
    $conn->close();
    
    return $categoria_id; 
}

// Manejar la adición de una nueva tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarea = $_POST['tarea'];
    $usuario_id = $_SESSION['usuario_id']; // Asegúrate de tener el ID del usuario en la sesión
    $categoria = 'estudios'; // Nombre de la categoría

    $categoria_id = obtenerCategoriaID($categoria, $usuario_id);

    if ($categoria_id !== null) {
        $sql = "INSERT INTO tareas (descripcion, categoria_id, usuario_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $tarea, $categoria_id, $usuario_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error: Categoría no encontrada.";
    }

    header("Location: estudios.php");
    exit();
}

// Obtener tareas de la categoría
$usuario_id = $_SESSION['usuario_id'];
$categoria_id = obtenerCategoriaID('estudios', $usuario_id);
$sql = "SELECT * FROM tareas WHERE categoria_id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $categoria_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('https://img.freepik.com/fotos-premium/simplicidad-pura-ramas-arboles-minimalistas-superficie-blanca_875825-7510.jpg?w=826');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .page-title {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }
        .task-list {
            margin-top: 20px;
        }
        .task-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Tareas de Estudios</h1>
        
        <form method="POST">
            <input type="text" name="tarea" placeholder="Nueva tarea" required>
            <button type="submit">Agregar Tarea</button>
        </form>

        <div class="task-list">
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <div class="task-item">
                    <?php echo htmlspecialchars($fila['descripcion']); ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
