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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Para los íconos -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
        .password-container {
            position: relative;
        }

        #contrasena {
            padding-right: 40px; /* Espacio para el ícono */
        }

        .toggle-password {
            position: absolute;
            right: 10px; /* Espacio desde la derecha */
            top: 50%;
            transform: translateY(-80%); /* Centra el ícono verticalmente */
            cursor: pointer;
            color: #888;
        }

        .toggle-password:hover {
            color: #000;
        }

        .social-login {
            display: flex;
            flex-direction: column; /* Coloca los botones en columna */
            align-items: center; /* Centra los botones horizontalmente */
            margin-top: 20px;
        }

        .social-button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            width: 100%; /* Asegura que ambos botones tengan el mismo ancho */
            margin-bottom: 10px; /* Espacio entre los botones */
        }

        .google {
            background-color: #db4437;
            color: white;
        }

        .facebook {
            background-color: #4267B2;
            color: white;
        }
    </style>
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
            <div class="password-container">
                <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                <i class="toggle-password fas fa-eye" id="togglePassword"></i>
            </div>
            
            <div class="button-container">
                <button type="submit">Registrar</button>
                <a href="login.php" class="registro-button">Ir a login</a>
            </div>
        </form>
        
        <?php if ($mensaje): ?>
            <p class="mensaje"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <div class="social-login">
            <button class="social-button facebook" onclick="location.href='https://www.facebook.com/v10.0/dialog/oauth?client_id=YOUR_APP_ID&redirect_uri=YOUR_REDIRECT_URI&scope=email';">
                <i class="fab fa-facebook-f"></i> Registrar con Facebook
            </button>
            <button class="social-button google" onclick="location.href='https://accounts.google.com/o/oauth2/auth?client_id=YOUR_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=code&scope=email';">
                <i class="fab fa-google"></i> Registrar con Google
            </button>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('contrasena');

        togglePassword.addEventListener('click', function () {
            // Alternar entre mostrar y ocultar la contraseña
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
