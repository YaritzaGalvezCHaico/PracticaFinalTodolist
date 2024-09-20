<?php  
session_start(); // Inicia la sesión

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Verificar si los datos del formulario están presentes
    if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
        $correo = $_POST['usuario'];  
        $contrasena = $_POST['contrasena'];  

        // Consulta para verificar el usuario usando consultas preparadas
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();  

        if ($result->num_rows > 0) {  
            $row = $result->fetch_assoc();  
            // Verificar la contraseña  
            if (password_verify($contrasena, $row['contrasena'])) {  
                $_SESSION['usuario_id'] = $row['id']; // Guardar el ID del usuario en la sesión
                $_SESSION['correo'] = $correo; // Guardar el correo en la sesión si es necesario
                header("Location: tareas.php"); // Redirigir a la página de tareas
                exit();
            } else {  
                header("Location: login.php?error=contrasena"); // Redirigir con error de contraseña
                exit();
            }  
        } else {  
            header("Location: login.php?error=no_registrado"); // Redirigir con error de usuario no registrado
            exit();
        }  

        $stmt->close(); // Cerrar la declaración
    } else {
        // Redirigir si los datos del formulario no están presentes
        header("Location: login.php?error=datos_faltantes");
        exit();
    }
}  
$conn->close();  
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css"> <!-- Estilos específicos para login -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST"> <!-- El action está vacío para enviar al mismo archivo -->
            <label for="usuario">Usuario</label>
            <input type="email" id="usuario" name="usuario" placeholder="Correo electrónico" required>
            
            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
            
            <div class="button-container">
                <button type="submit">Login</button>
                <a href="registro.html" class="registro-button">Ir a registro</a>
            </div>
        </form>
    </div>
</body>
</html>
