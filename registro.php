<?php  
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos
$servername = "localhost"; // Cambia esto si tu servidor MySQL tiene un nombre diferente  
$username = "root"; // Cambia esto por tu nombre de usuario de MySQL  
$password = ""; // Cambia esto por tu contraseña de MySQL  
$dbname = "tareas_db"; // Nombre de la base de datos  

// Crear conexión  
$conn = new mysqli($servername, $username, $password, $dbname);  

// Verificar la conexión  
if ($conn->connect_error) {  
    die("Conexión fallida: " . $conn->connect_error);  
}  
// echo "Conexión exitosa.<br>"; // Mensaje de depuración (puedes comentarlo o eliminarlo)

// Inicializar mensaje
$mensaje = "";

// Registrar nuevo usuario  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Obtener los datos del formulario
    $correo = $conn->real_escape_string($_POST['correo']);  
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT); // Hash de la contraseña  
    $nombre_usuario = $conn->real_escape_string($_POST['nombre_usuario']); // Obtener nombre de usuario

    // echo "Datos recibidos: Correo - $correo, Nombre de Usuario - $nombre_usuario<br>"; // Mensaje de depuración (puedes comentarlo o eliminarlo)

    // Verificar si el correo o nombre de usuario ya está registrado
    $sql_check = "SELECT id FROM usuarios WHERE correo = '$correo' OR nombre_usuario = '$nombre_usuario'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $mensaje = "Este correo electrónico o nombre de usuario ya está registrado.";
    } else {
        // Si el correo y nombre de usuario no están registrados, insertarlos en la base de datos
        $sql = "INSERT INTO usuarios (correo, contrasena, nombre_usuario) VALUES ('$correo', '$contrasena', '$nombre_usuario')";
        
        if ($conn->query($sql) === TRUE) {  
            $mensaje = "Registro exitoso!";  
        } else {  
            $mensaje = "Error: " . $conn->error;  
        }  
    }
}  

$conn->close();  

// Mostrar el mensaje en la página de registro
echo $mensaje; 
?>
