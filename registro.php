<?php  
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos
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

// Inicializar mensaje
$mensaje = "";

// Registrar nuevo usuario  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Obtener los datos del formulario
    $correo = $conn->real_escape_string($_POST['correo']);  
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT); 
    $nombre_usuario = $conn->real_escape_string($_POST['nombre_usuario']); 

    // Verificar si el correo o nombre de usuario ya está registrado
    $sql_check = "SELECT id FROM usuarios WHERE correo = '$correo' OR nombre_usuario = '$nombre_usuario'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $mensaje = "Este correo electrónico o nombre de usuario ya está registrado.";
    } else {
        // Insertarlos en la base de datos
        $sql = "INSERT INTO usuarios (correo, contrasena, nombre_usuario) VALUES ('$correo', '$contrasena', '$nombre_usuario')";
        
        if ($conn->query($sql) === TRUE) {  
            $mensaje = "Registro exitoso!";  
        } else {  
            $mensaje = "Error: " . $conn->error;  
        }  
    }
}  

$conn->close();  
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="login.css"> <!-- Enlaza tu CSS aquí -->
</head>
<body>
    <div class="login-container">
        <h2>Registro</h2>
        <form action="" method="POST">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre de usuario" required>
            
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
            
            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
            
            <div class="button-container">
                <button type="submit">Registrar</button>
                <a href="login.html" class="registro-button">Ir a login</a>
            </div>
        </form>
        
        <?php if ($mensaje): ?>
            <p class="mensaje"><?php echo $mensaje; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

