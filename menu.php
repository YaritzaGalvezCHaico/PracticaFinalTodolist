<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de tener tu CSS aquí -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Para iconos -->
    <style>
        /* Estilo básico para la página */
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://img.freepik.com/fotos-premium/simplicidad-pura-ramas-arboles-minimalistas-superficie-blanca_875825-7510.jpg?w=826'); /* Imagen de fondo */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .container {
    max-width: 800px; /* Cambia el tamaño máximo para hacerlo más pequeño */
    margin: 100px auto; /* Mantiene el espaciado superior y centra el contenedor */
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco con mayor opacidad */
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
    position: relative; /* Permitir el ajuste de posición */
    left: 70px; /* Mueve el contenedor 100px hacia la derecha */
}


        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5rem; /* Tamaño de fuente más grande */
            margin: 0;
        }

        .menu-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); /* Tamaño ajustado */
            gap: 20px;
        }

        .menu-item {
            background-color: rgba(255, 192, 203, 0.8); /* Color pastel con transparencia */
            color: #333;
            padding: 15px; /* Espaciado más grande */
            border-radius: 12px;
            text-align: center;
            transition: transform 0.2s ease, background-color 0.3s ease; /* Transiciones suaves */
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        }

        .menu-item:hover {
            transform: scale(1.05); /* Efecto de "vibración" al pasar el mouse */
            background-color: rgba(255, 105, 180, 0.9); /* Color pastel más oscuro al pasar el mouse */
        }

        .menu-item i {
            font-size: 40px; /* Tamaño de icono más grande */
            margin-bottom: 10px;
            transition: color 0.3s ease; /* Transición para color del icono */
        }

        .menu-item:hover i {
            color: #ff69b4; /* Color del icono al pasar el mouse */
        }
    </style>
</head>
<body>
    <!-- Incluir la barra de menú -->
    <?php include 'barra_menu.php'; ?>

    <div class="container">
        <div class="header">
            <h1>Bienvenido al Menú Principal</h1> <!-- Mensaje de bienvenida -->
        </div>
        <div class="menu-items">
            <div class="menu-item" onclick="window.location.href='tareas.php'">
                <i class="fas fa-tasks"></i>
                <h3>Tareas</h3>
            </div>
            <div class="menu-item" onclick="window.location.href='notas.php'">
                <i class="fas fa-sticky-note"></i>
                <h3>Notas</h3>
            </div>
            <div class="menu-item" onclick="window.location.href='categorias.php'">
                <i class="fas fa-folder"></i>
                <h3>Categorías</h3>
            </div>
            <div class="menu-item" onclick="window.location.href='perfil.php'">
                <i class="fas fa-user"></i>
                <h3>Perfil</h3>
            </div>
            <div class="menu-item" onclick="window.location.href='ajustes.php'">
                <i class="fas fa-cog"></i>
                <h3>Ajustes</h3>
            </div>
            <div class="menu-item" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt"></i>
                <h3>Cerrar Sesión</h3>
            </div>
        </div>
    </div>
</body>
</html>
