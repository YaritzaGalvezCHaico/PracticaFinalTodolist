<?php

ob_start(); // Iniciar el buffer de salida

session_start(); // Iniciar la sesión

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profileImage'])) {
    $targetDir = "uploads/"; // Specify the upload directory
    $targetFile = $targetDir . basename($_FILES["profileImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Check file size (5MB maximum)
    if ($_FILES["profileImage"]["size"] > 5000000) {
        echo "El archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "El archivo no se pudo subir.";
    } else {
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
            echo "El archivo " . htmlspecialchars(basename($_FILES["profileImage"]["name"])) . " ha sido subido.";
        } else {
            echo "Error al subir el archivo.";
        }
    }
}


// Simulación de datos de usuario (esto debería venir de tu base de datos)
$user_logged_in = isset($_SESSION['username']); // Verificar si el usuario ha iniciado sesión
$username = $user_logged_in ? $_SESSION['username'] : 'Invitado'; // Obtener el nombre del usuario
$notification_count = 3; // Contador de notificaciones (esto debería ser dinámico)

ob_end_flush(); // Enviar el contenido del buffer al navegador
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barra de Menú</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Delius+Swash+Caps&display=swap" rel="stylesheet">

    <style>
        /* Estilo general para la barra de menú */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        .menu {
            background-color: #ffffff; /* Fondo blanco */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 0;
            width: 80px;
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1), 0px 0 0px rgba(0, 0, 0, 0.1); /* Sombra ajustada */
        }

        .app-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 0px;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .app-icon:hover {
            transform: scale(1.1);
        }

        .menu-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #000000; /* Texto e iconos en negro */
            padding: 10px;
            border-radius: 12px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            margin: 70px 0 -15px; /* Espacio adicional arriba y margen reducido abajo */
            width: 100%;
        }

        .menu-icon:hover {
            background-color: rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
            box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);
        }

        .icon {
            font-size: 21px;
            color: #000000; /* Iconos de color negro */
        }

        .config-icon {
            margin-top: auto; /* Mantiene la configuración en la parte inferior */
        }

        .close-section {
            display: flex; /* Para centrar el contenido */
            align-items: center; /* Centrar verticalmente el ícono */
            justify-content: center; /* Centrar horizontalmente */
            margin-top: 65px; /* Ajustado el espacio entre configuración y cerrar */
            text-decoration: none;
            color: #000000; /* Texto en negro */
            padding: 10px;
            border-radius: 12px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            width: 100%;
            margin-bottom: 35px; /* Espacio entre los íconos de cerrar y la parte inferior */
        }

        .close-section:hover {
            background-color: rgba(255, 0, 0, 0.1);
            transform: translateY(-5px);
            box-shadow: 0 0px 5px rgba(0, 0, 0, 0.1);
        }

        .close-section i {
            color: #dc3545; /* Ícono de cerrar en rojo */
        }

        /* Estilos para la barra superior */
        .top-bar {
            background-color: #ffffff; /* Fondo blanco */
            display: flex;
            align-items: center;
            padding: 1px 9px;
            box-shadow: 0 0px 5px rgba(0, 0, 0, 0.1); /* Sombra solo en el lado inferior */
            position: fixed;
            top: 0;
            left: 0; /* Espacio para la barra lateral */
            right: 0;
            z-index: 100;
            border-top: 1px solid transparent; /* Opcional: elimina la sombra en la parte superior */
        }



        .search-bar {
            flex: 1;
            display: flex;
            justify-content: center; /* Centrar la barra de búsqueda */
            align-items: center;
            margin: 0; /* Sin margen */
        }

        .search-input {
            width: 400px; /* Ancho reducido para la barra de búsqueda */
            padding: 8px; /* Padding reducido */
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
            font-size: 14px; /* Tamaño de fuente ajustado */
        }
        .search-type {
    margin-right: 10px; /* Espacio entre el menú y el campo de búsqueda */
    padding: 7px; /* Espaciado interno */
    border-radius: 4px; /* Bordes redondeados */
    border: 1px solid #ccc; /* Borde gris claro */
}



        .notification-icon {
            position: relative;
            margin-left: 20px;
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545; /* Color rojo */
            color: white;
            border-radius: 50%;
            padding: 5px 8px;
            font-size: 12px;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 40px; /* Espacio desde el icono */
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 100;
            width: 200px; /* Ancho del menú */
        }

        .notification-icon:hover .notification-dropdown {
            display: block;
        }

        .user-menu {
            position: relative;
            margin-left: 20px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 40px; /* Espacio desde la foto de perfil */
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .user-menu:hover .user-dropdown {
            display: block;
        }

        .user-dropdown a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
        }

        .user-dropdown a:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 600px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-input {
                width: 100%; /* Ancho completo en dispositivos pequeños */
            }
        }
        
        :root {
            --button-background: linear-gradient(to right, rgba(255, 0, 255, 0.4), rgba(147, 112, 219, 0.4), rgba(0, 122, 204, 0.4)); /* Degradado fucsia, morado y celeste */
            --button-background-hover: linear-gradient(to right, rgba(255, 0, 255, 0.6), rgba(147, 112, 219, 0.6), rgba(0, 122, 204, 0.6)); /* Degradado más oscuro al pasar el mouse */
            --button-text-color: #fff; /* Color del texto */
        }

        .search-button {
            background: var(--button-background); /* Usar variable para el fondo */
            border: none; /* Sin borde */
            color: var(--button-text-color); /* Usar variable para el color del texto */
            padding: 8px 23px; /* Aumentar el padding para un fondo más ancho */
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px; /* Espacio entre la barra de búsqueda y el botón */
            transition: background 0.3s, color 0.3s, box-shadow 0.3s; /* Transiciones */
            font-size: 16px; /* Tamaño de fuente */
        }

        .search-button:hover {
            background: var(--button-background-hover); /* Cambiar a un fondo más oscuro al pasar el mouse */
            color: var(--button-text-color); /* Asegurar que el texto siga siendo blanco */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Aumentar la sombra al pasar el mouse */
        }

        .app-icon-top {
    width: 65px;  /* Cambia este valor según el tamaño deseado */
    height: auto; /* Mantiene la proporción de la imagen */
    margin-right: 10px; /* Espacio entre el icono y la barra de búsqueda */
}

.app-title {
    font-size: 35px; /* Tamaño de fuente */
    font-weight: bold; /* Negrita */
    margin-left: 2px; /* Espacio entre el icono y el título */
    color: #333; /* Color del texto */
    display: flex; /* Alineación en línea */
    align-items: center; /* Centra verticalmente el texto con el icono */
}

.upload-button {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
}
.upload-button:hover {
    background-color: #0056b3;
}



    </style>
</head>
<body>
    <div class="top-bar">
    <img src="https://i.pinimg.com/originals/37/3c/8d/373c8d7aedb2e0de4293f4953d40088a.jpg" alt="Icono de la aplicación" class="app-icon-top">
    <span class="app-title">ToDoList</span> <!-- Título agregado -->
    <div class="search-bar">
    <select class="search-type" aria-label="Tipo de búsqueda" id="searchType">
        <option value="notas">Notas</option>
        <option value="categorias">Categorías</option>
        <option value="tareas">Tareas</option>
    </select>
    <input type="text" class="search-input" placeholder="Buscar..." id="searchInput">
    <button class="search-button" id="searchButton"><i class="fas fa-search"></i></button>
</div>

<div class="user-menu">
    <img src="profile.jpg" alt="Perfil" class="profile-pic" id="profilePic" style="cursor: pointer;">
    <input type="file" accept="image/*" id="fileInput" style="display: none;">
    <div class="user-dropdown">
        <a href="#">Perfil de <?php echo $username; ?></a>
    </div>
</div>

        <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-count"><?php echo $notification_count; ?></span>
            <div class="notification-dropdown">
                <a href="#">Notificación 1</a>
                <a href="#">Notificación 2</a>
                <a href="#">Notificación 3</a>
            </div>
        </div>
    </div>
    
    <div class="menu">
    <a href="menu.php" class="menu-icon"><i class="fas fa-home icon"></i></a>
    <a href="tareas.php" class="menu-icon"><i class="fas fa-tasks icon"></i></a>
    <a href="categorias.php" class="menu-icon"><i class="fas fa-tags icon"></i></a>
    <a href="notas.php" class="menu-icon"><i class="fas fa-sticky-note icon"></i></a>
    <a href="configuracion.php" class="menu-icon config-icon"><i class="fas fa-cogs icon"></i></a>
    <a href="logout.php" class="close-section"><i class="fas fa-sign-out-alt icon"></i></a>
</div>

<script>
        // Habilitar la barra de búsqueda y el botón al seleccionar un tipo
        document.getElementById('searchType').addEventListener('change', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            if (this.value) {
                searchInput.disabled = false;
                searchButton.disabled = false;
                searchInput.focus();
            } else {
                searchInput.disabled = true;
                searchButton.disabled = true;
            }
        });

        // Lógica para buscar
        document.getElementById('searchButton').addEventListener('click', function() {
            // Obtener el valor del input de búsqueda
            const searchTerm = document.getElementById('searchInput').value.trim();
            // Obtener el tipo de búsqueda seleccionado
            const searchType = document.getElementById('searchType').value;

            // Comprobar si se ha ingresado un término de búsqueda
            if (searchTerm) {
                // Redirigir a la página correspondiente
                switch (searchType) {
                    case 'notas':
                        searchInPage('notas.php', searchTerm);
                        break;
                    case 'categorias':
                        searchInPage('categorias.php', searchTerm);
                        break;
                    case 'tareas':
                        searchInPage('tareas.php', searchTerm);
                        break;
                    default:
                        alert('Tipo de búsqueda no válido.');
                        break;
                }
            } else {
                alert('Por favor, ingresa un término de búsqueda.');
            }
        });

        // Función para buscar en la página correspondiente
        function searchInPage(page, searchTerm) {
            // Aquí podrías hacer una llamada al backend para verificar si el término existe
            // Por ahora, redirigimos a la página de búsqueda
            window.location.href = `${page}?search=${encodeURIComponent(searchTerm)}`;
            
            // Simulación de la búsqueda
            // Aquí deberías incluir la lógica que valida si el término existe en la base de datos
            // Ejemplo:
            /*
            const found = false; // Cambia esto según tu lógica de búsqueda
            if (!found) {
                alert(`No se encontró ${searchTerm} en ${page}.`);
            }
            */
        }
    </script>

<script>
    const profilePic = document.getElementById('profilePic');
    const fileInput = document.getElementById('fileInput');

    // Abrir el selector de archivos al hacer clic en la imagen de perfil
    profilePic.addEventListener('click', function() {
        fileInput.click();
    });

    // Cambiar la imagen de perfil al seleccionar un archivo
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePic.src = e.target.result; // Cambiar la imagen de perfil
            };
            reader.readAsDataURL(file);
        }
    });
</script>


</body>
</html>
