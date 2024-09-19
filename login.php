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
                header("Location: login.html?error=contrasena"); // Redirigir con error de contraseña
                exit();
            }  
        } else {  
            header("Location: login.html?error=no_registrado"); // Redirigir con error de usuario no registrado
            exit();
        }  

        $stmt->close(); // Cerrar la declaración
    } else {
        // Redirigir si los datos del formulario no están presentes
        header("Location: login.html?error=datos_faltantes");
        exit();
    }
}  

$conn->close();  
?>

