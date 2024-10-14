<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('https://img.freepik.com/fotos-premium/simplicidad-pura-ramas-arboles-minimalistas-superficie-blanca_875825-7510.jpg?w=826');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            max-width: 800px; /* Ajusta el tamaño máximo según lo necesites */
            margin: 100px auto; /* Mantiene el espaciado superior y centra el contenedor */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco con mayor opacidad */
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
            position: relative; /* Permitir el ajuste de posición */
            left: 50px; /* Mueve el contenedor 50px hacia la derecha */
        }

        .page-title {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .categories-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Usa grid para un diseño responsivo */
            gap: 15px; /* Espacio entre las tarjetas */
        }

        .category-card {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo de las tarjetas */
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra suave para las tarjetas */
            transition: transform 0.2s, box-shadow 0.2s; /* Efecto de animación suave */
            text-decoration: none; /* Sin subrayado en el enlace */
            color: inherit; /* Hereda el color del texto */
        }

        .category-card:hover {
            transform: translateY(-5px); /* Levanta la tarjeta al pasar el ratón */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Aumenta la sombra */
        }

        .category-icon {
            font-size: 3em; /* Tamaño del icono */
            margin-bottom: 10px; /* Espacio entre el icono y el texto */
            color: #007bff; /* Color del icono */
        }
    </style>
</head>
<body>
    <?php include 'barra_menu.php'; ?> <!-- Incluye la barra de menú -->

    <div class="container">
        <h1 class="page-title">Categorías</h1>
        <div class="categories-list">
            <a href="estudios.php" class="category-card">
                <i class="fas fa-book category-icon"></i>
                <div>Estudios</div>
            </a>
            <a href="trabajo.php" class="category-card">
                <i class="fas fa-briefcase category-icon"></i>
                <div>Trabajo</div>
            </a>
            <a href="hogar.php" class="category-card">
                <i class="fas fa-home category-icon"></i>
                <div>Hogar</div>
            </a>
        </div>
    </div>
</body>
</html>
