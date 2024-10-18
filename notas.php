<?php
// Comprobación de la sesión antes de iniciar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conexion = new mysqli('localhost', 'usuario', 'tu_contraseña', 'tareas_db');

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener el ID del usuario de la sesión
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_id) {
    $contenido = $conexion->real_escape_string($_POST['contenido']);

    // Verifica que el usuario existe en la tabla usuarios antes de insertar
    $verificar_usuario = $conexion->query("SELECT id FROM usuarios WHERE id = $usuario_id");
    
    if ($verificar_usuario->num_rows > 0) {
        // Insertar la nueva nota en la base de datos si el usuario existe
        $query = "INSERT INTO notas (contenido, usuario_id) VALUES ('$contenido', $usuario_id)";
        
        if ($conexion->query($query)) {
            echo "<p style='color: green;'>Nota guardada con éxito.</p>";
        } else {
            echo "<p style='color: red;'>Error al guardar la nota.</p>";
        }
    } else {
        echo "<p style='color: red;'>Error: Usuario no existe.</p>";
    }
}

// Consultar las notas del usuario si está autenticado
if ($usuario_id) {
    $result = $conexion->query("SELECT * FROM notas WHERE usuario_id = $usuario_id ORDER BY creado_en DESC");
} else {
    $result = null; // Si el usuario no está autenticado, no se recuperan notas
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlace a tu archivo CSS -->
    <style>
        body {
            background-image: url('https://img.freepik.com/fotos-premium/simplicidad-pura-ramas-arboles-minimalistas-superficie-blanca_875825-7510.jpg?w=826');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco con mayor opacidad */
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff; /* Color del título */
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            resize: none;
            font-size: 16px; /* Tamaño de fuente del textarea */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra para el textarea */
        }
        button {
            background-color: #28a745; /* Verde para el botón */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px; /* Tamaño de fuente del botón */
            transition: background-color 0.3s ease; /* Transición suave */
        }
        button:hover {
            background-color: #218838; /* Verde más oscuro al pasar el mouse */
        }
        .note {
            background-color: #e9ecef; /* Fondo claro para las notas */
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra para las notas */
        }
        small {
            display: block;
            color: #6c757d; /* Color más suave para la fecha */
        }
        p.success {
            color: green;
            text-align: center;
        }
        p.error {
            color: red;
            text-align: center;
        }
        /* Estilo de la barra de menú */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco con transparencia */
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Sombra para la barra de menú */
            margin-bottom: 20px; /* Espacio inferior para separar del contenido */
        }

        .navbar a {
            text-decoration: none;
            color: #333; /* Color del texto */
            margin: 0 15px; /* Espacio entre los enlaces */
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s; /* Efecto de transición para el fondo */
        }

        .navbar a:hover {
            background-color: #007bff; /* Color de fondo al pasar el mouse */
            color: white; /* Color del texto al pasar el mouse */
        }
    </style>
</head>
<body>

<?php
// Incluir la barra de menú
include('barra_menu.php'); 
?>

<div class="container">
    <h2>Guardar Nota</h2>
    <form action="notas.php" method="POST">
        <textarea name="contenido" required placeholder="Escribe tu nota aquí..."></textarea>
        <button type="submit">Guardar Nota</button>
    </form>

    <h2>Notas Guardadas</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="note">
                <p><?php echo htmlspecialchars($row['contenido']); ?></p>
                <small>Creado el: <?php echo $row['creado_en']; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay notas guardadas.</p>
    <?php endif; ?>
</div>

</body>
</html>
