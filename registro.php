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
    /* Importar fuente Open Sans desde Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');

/* Estilo general para la página de login */
body {
    font-family: 'Open Sans', sans-serif; /* Aplicar la fuente Open Sans para un diseño más moderno y legible */
    background-color: #ffffff; /* Fondo blanco para mantener la simplicidad y claridad */
    display: flex;
    justify-content: center; /* Centra el contenido horizontalmente */
    align-items: center; /* Centra el contenido verticalmente */
    height: 100vh; /* Asegura que la página ocupe toda la altura de la ventana */
    margin: 0; /* Elimina el margen por defecto */
}

.login-container {
    background-color: white; /* Fondo blanco del contenedor para un look limpio */
    padding: 30px; /* Aumenta el espaciado interno del contenedor */
    border-radius: 8px; /* Bordes redondeados para un diseño más amigable */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra sutil para darle profundidad al contenedor */
    width: 300px; /* Ancho fijo del contenedor */
    text-align: center; /* Centra el texto dentro del contenedor */
    margin-top: 0%; /* Ajusta el contenedor hacia arriba para centrar mejor en pantallas grandes */
}


h2 {
    margin-bottom: 25px; /* Espacio debajo del título */
    font-size: 40px; /* Tamaño del título grande para destacar */
}

label {
    display: block; /* Hace que las etiquetas ocupen toda la línea */
    margin-bottom: 10px; /* Espacio debajo de las etiquetas */
    text-align: left; /* Alinea el texto de las etiquetas a la izquierda */
}

input[type="nombre_usuario"],
input[type="email"],
input[type="password"] {
    width: calc(100% - 20px); /* Ajusta el ancho para el padding */
    padding: 10px; /* Espaciado interno de los campos de entrada */
    margin-bottom: 14px; /* Espacio debajo de los campos de entrada */
    border: 1px solid #ccc; /* Borde gris claro */
    border-radius: 5px; /* Bordes redondeados en los campos de entrada */
    font-size: 14px; /* Tamaño de fuente para legibilidad */
    box-sizing: border-box; /* Incluye el padding en el cálculo del ancho */
    background-color: #ffffff; /* Fondo blanco para los campos de entrada */
}

/* Estilo del contenedor de los botones */
.button-container {
    display: flex;
    justify-content: center; /* Centra los botones horizontalmente */
    gap: 20px; /* Espacio entre los botones */
    margin-top: 20px; /* Espacio encima del contenedor de botones */
}

/* Botón de registro */
button {
    width: 120px; /* Tamaño del botón para una mejor interacción */
    padding: 10px; /* Espaciado interno del botón */
    background-color: #000000; /* Fondo negro del botón para destacar */
    color: white; /* Texto blanco para contraste */
    border: none; /* Sin borde para un diseño limpio */
    border-radius: 5px; /* Bordes redondeados del botón */
    font-size: 14px; /* Tamaño de fuente del botón */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    display: inline-block; /* Alinea los botones en línea */
}

button:hover {
    background-color: #000000; /* Negro más oscuro al pasar el mouse para un efecto de hover */
}

/* Estilo para el botón de ir a login */
.registro-button {
    padding: 10px; /* Espaciado interno del botón de registro */
    color: #030303; /* Texto negro para el botón de registro */
    text-decoration: none; /* Elimina el subrayado del texto */
    border: 1px solid #000000; /* Borde negro del botón de registro */
    border-radius: 5px; /* Bordes redondeados del botón de registro */
    font-size: 14px; /* Tamaño de fuente del botón de registro */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón de registro */
    background-color: white; /* Fondo blanco para el botón de registro */
    display: inline-block; /* Alinea el botón en la misma línea que otros elementos */
    line-height: 1.5; /* Mejora la alineación vertical del texto */
}

.registro-button:hover {
    background-color: #f8f9fa; /* Fondo gris claro al pasar el mouse para un efecto de hover */
    text-decoration: underline; /* Subraya el texto al pasar el mouse */
}

/* Estilo para los mensajes */
.mensaje {
    margin-top: 20px; /* Espacio encima del mensaje */
    font-size: 14px; /* Tamaño de fuente para los mensajes */
    color: #ff0000; /* Color rojo para los mensajes de error */
}

/* Consultas de medios para pantallas más pequeñas */
@media (max-width: 768px) {
    .login-container {
        width: 80%; /* Ancho del contenedor ajustado para pantallas más pequeñas */
        padding: 14px; /* Espaciado interno reducido */
        margin-top: -5%; /* Ajusta el margen para pantallas medianas */
    }

    h2 {
        font-size: 20px; /* Tamaño del título reducido */
    }

    input[type="email"],
    input[type="password"] {
        font-size: 14px; /* Tamaño de fuente reducido en campos de entrada */
    }

    button {
        width: 100px; /* Tamaño reducido del botón */
        font-size: 14px; /* Tamaño de fuente reducido del botón */
    }

    .registro-button {
        font-size: 14px; /* Tamaño de fuente reducido del botón de registro */
        padding: 8px; /* Espaciado interno reducido */
    }
}

@media (max-width: 480px) {
    .login-container {
        width: 100%; /* Ancho del contenedor aún más ajustado para pantallas pequeñas */
        padding: 10px; /* Espaciado interno más reducido */
        margin-top: 0; /* Ajusta el margen para pantallas muy pequeñas */
    }

    h2 {
        font-size: 25px; /* Tamaño del título más pequeño */
    }

    input[type="email"],
    input[type="password"] {
        font-size: 14px; /* Tamaño de fuente aún más pequeño en campos de entrada */
    }

    button {
        width: 80px; /* Tamaño aún más reducido del botón */
        font-size: 14px; /* Tamaño de fuente aún más pequeño del botón */
    }

    .registro-button {
        font-size: 14px; /* Tamaño de fuente aún más pequeño del botón de registro */
        padding: 6px; /* Espaciado interno aún más reducido */
    }
}

/* Estilo unificado para todos los campos de entrada (texto, correo, contraseña) */
input[type="nombre_usuario"],
input[type="email"],
input[type="password"] {
    width: 100%; /* Cambia esto a 100% para ocupar todo el ancho del contenedor */
    max-width: 300px; /* Limita el ancho máximo para mantenerlo en el medio */
    padding: 10px;
    margin: 0 auto 15px auto; /* Centra el cuadro y agrega espacio debajo */
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box; /* Asegúrate de que el padding y el borde se incluyan en el ancho */
    background-color: #ffffff;
}

/* Botón de login y registro con tamaño uniforme */
button,
.registro-button {
    width: 120px; /* Mismo ancho para ambos botones */
    padding: 10px; /* Mismo espaciado interno para ambos */
    background-color: #000000; /* Fondo negro para el botón de login */
    color: white; /* Texto blanco para contraste */
    border: none; /* Sin borde para un diseño limpio */
    border-radius: 5px; /* Bordes redondeados para ambos botones */
    font-size: 14px; /* Mismo tamaño de fuente para ambos */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    display: inline-block; /* Alinea los botones en línea */
}
/* Estilo base para ambos botones */
button, .registro-button {
    width: 120px; /* Ancho común para ambos botones */
    padding: 10px; /* Espaciado uniforme */
    font-size: 14px; /* Tamaño de fuente igual */
    border-radius: 5px; /* Bordes redondeados iguales */
    cursor: pointer; /* Mismo estilo de cursor */
    text-align: center; /* Alineación central */
    display: inline-block; /* Para asegurar la alineación horizontal */
    box-sizing: border-box; /* Asegura que el padding se incluya en el tamaño total */
}

/* Estilo del botón de login */
button {
    background-color: #000000; /* Fondo negro */
    color: white; /* Texto blanco */
    border: none; /* Sin borde */
}

button:hover {
    background-color: #333333; /* Color de hover para login */
}

/* Estilo del botón de registro */
.registro-button {
    background-color: white; /* Fondo blanco */
    color: #030303; /* Texto negro */
    border: 1px solid #000000; /* Borde negro */
    text-decoration: none; /* Sin subrayado */
}

.registro-button:hover {
    background-color: #f8f9fa; /* Color de hover para registro */
}


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
            background-color: rgba(234, 184, 255, 0.8); /* Color morado pastel con 80% de opacidad */
            color: black;
            border: none; /* Elimina el borde del botón */
            border-radius: 5px; /* Añade esquinas redondeadas */
            padding: 10px 15px; /* Espaciado interno */
            cursor: pointer; /* Cambia el cursor al pasar el mouse */
            transition: background-color 0.3s; /* Transición suave para el color */
        }

        .facebook {
            background-color: rgba(163, 212, 224, 0.8); /* Color celeste pastel con 80% de opacidad */
            color: black;
            border: none; /* Elimina el borde del botón */
            border-radius: 5px; /* Añade esquinas redondeadas */
            padding: 10px 15px; /* Espaciado interno */
            cursor: pointer; /* Cambia el cursor al pasar el mouse */
            transition: background-color 0.3s; /* Transición suave para el color */
        }

        /* Efecto al pasar el mouse */
        .google:hover {
            background-color: rgba(234, 184, 255, 1); /* Color morado pastel sin transparencia */
        }

        .facebook:hover {
            background-color: rgba(163, 212, 224, 1); /* Color celeste pastel sin transparencia */
        }

        .social-button i {
            margin-right: 10px; /* Espacio entre el ícono y el texto */
            margin-left: -15px; /* Ajuste para mover el ícono un poco más a la izquierda */
        }

        .app-icon {
            width: 120px; /* Aumenta el tamaño del ícono */
            height: 120px; /* Mantén el mismo valor para que sea un cuadrado */
            border-radius: 30px; /* Bordes redondeados */
            overflow: hidden; /* Asegura que la imagen no se salga del contenedor */
            margin: 0 auto 20px auto; /* Centra el ícono y añade espacio debajo */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-icon img {
            width: 100%; /* Asegura que la imagen ocupe todo el contenedor */
            height: 100%; /* Asegura que la imagen ocupe todo el contenedor */
            object-fit: cover; /* Ajusta la imagen para que no se distorsione */
        }

        /* Media queries para ajustar en pantallas pequeñas */
        @media (max-width: 480px) {
            .app-icon {
                width: 80px; /* Ancho reducido en pantallas pequeñas */
                height: 80px; /* Alto reducido en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Ícono de la aplicación -->
        <div class="app-icon">
            <img src="https://i.pinimg.com/originals/37/3c/8d/373c8d7aedb2e0de4293f4953d40088a.jpg" alt="Icono de la aplicación">
        </div>
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
