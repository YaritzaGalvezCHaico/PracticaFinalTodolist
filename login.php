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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Para los íconos -->
    <style>
        .password-container {
            position: relative;
        }

        #contrasena {
            padding-right: 40px; /* Espacio para el ícono */
            height: 40px; /* Altura del campo de entrada */
        }

        .toggle-password {
            position: absolute;
            right: 10px; /* Mover más hacia la izquierda */
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
            display: flex; /* Para alinear el ícono y el texto */
            align-items: center; /* Centra el ícono y el texto verticalmente */
            justify-content: center; /* Centra el texto horizontalmente */
        }

        .google {
            background-color: #db4437;
            color: white;
        }

        .facebook {
            background-color: #4267B2;
            color: white;
        }

        .social-button i {
            margin-right: 10px; /* Espacio entre el ícono y el texto */
            margin-left: -15px; /* Ajuste para mover el ícono un poco más a la izquierda */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST"> <!-- El action está vacío para enviar al mismo archivo -->
            <label for="usuario">Usuario</label>
            <input type="email" id="usuario" name="usuario" placeholder="Correo electrónico" required>
            
            <label for="contrasena">Contraseña</label>
            <div class="password-container">
                <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                <i class="toggle-password fas fa-eye" id="togglePassword"></i>
            </div>
            
            <div class="button-container">
                <button type="submit">Login</button>
                <a href="registro.php" class="registro-button">Ir a registro</a>
            </div>
        </form>

        <div class="social-login">
            <button class="social-button facebook" onclick="location.href='https://www.facebook.com/v10.0/dialog/oauth?client_id=YOUR_APP_ID&redirect_uri=YOUR_REDIRECT_URI&scope=email';">
                <i class="fab fa-facebook-f"></i> Iniciar sesión con Facebook
            </button>
            <button class="social-button google" onclick="location.href='https://accounts.google.com/o/oauth2/auth?client_id=YOUR_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=code&scope=email';">
                <i class="fab fa-google"></i> Iniciar sesión con Google
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
