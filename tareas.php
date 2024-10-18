<?php  
session_start(); // Inicia la sesión

// Incluir la barra de menú
include('barra_menu.php'); 

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redirigir al login si no hay sesión activa
    exit();
}

$usuario_id = $_SESSION['usuario_id']; // Obtener el ID del usuario desde la sesión
$mensaje = '';
$mensaje_tipo = ''; // Tipo de mensaje (success, confirm)
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'id'; // Orden por defecto

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


// Verificar si el nombre del usuario está disponible en la sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Consultar el nombre del usuario
    $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result_usuario = $stmt->get_result();

    if ($result_usuario->num_rows > 0) {
        $usuario = $result_usuario->fetch_assoc();
        // Guardar el nombre del usuario en la sesión
        $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];
    } else {
        die("Error: El usuario no existe.");
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nueva_tarea']) && !empty($_POST['descripcion'])) {
        $descripcion = trim($_POST['descripcion']);
        
        // Verificar si la tarea ya existe
        $stmt = $conn->prepare("SELECT id FROM tareas WHERE descripcion = ? AND usuario_id = ?");
        $stmt->bind_param("si", $descripcion, $usuario_id);
        $stmt->execute();
        $result_verificar_tarea = $stmt->get_result();

        if ($result_verificar_tarea->num_rows > 0) {
            $mensaje = 'La tarea con esta descripción ya existe. ¿Desea volver a crearla?';
            $mensaje_tipo = 'confirm'; // Tipo de mensaje: confirm
            $_SESSION['nueva_tarea_descripcion'] = $descripcion; // Guardar la descripción en la sesión para volver a usarla
        } else {
            $stmt = $conn->prepare("INSERT INTO tareas (descripcion, estado, usuario_id) VALUES (?, 'pendiente', ?)");
            $stmt->bind_param("si", $descripcion, $usuario_id);

            if ($stmt->execute()) {
                $mensaje = 'Tarea creada correctamente.';
                $mensaje_tipo = 'success'; // Tipo de mensaje: success
            } else {
                $mensaje = "Error: " . $stmt->error;
                $mensaje_tipo = 'error'; // Tipo de mensaje: error
            }
        }
    } elseif (isset($_POST['confirmar_creacion'])) {
        // Confirmar la creación de la tarea duplicada
        $descripcion = $_SESSION['nueva_tarea_descripcion'];
        $stmt = $conn->prepare("INSERT INTO tareas (descripcion, estado, usuario_id) VALUES (?, 'pendiente', ?)");
        $stmt->bind_param("si", $descripcion, $usuario_id);
        
        if ($stmt->execute()) {
            $mensaje = 'Tarea creada correctamente.';
            $mensaje_tipo = 'success'; // Tipo de mensaje: success
            unset($_SESSION['nueva_tarea_descripcion']); // Limpiar la descripción de la sesión
        } else {
            $mensaje = "Error: " . $stmt->error;
            $mensaje_tipo = 'error'; // Tipo de mensaje: error
        }
    } elseif (isset($_POST['cambiar_estado'])) {
        $tarea_id = intval($_POST['tarea_id']);
        $estado_actual = $_POST['estado_actual'];
        $nuevo_estado = ($estado_actual == 'pendiente') ? 'completada' : 'pendiente';

        $stmt = $conn->prepare("UPDATE tareas SET estado = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("sii", $nuevo_estado, $tarea_id, $usuario_id);
        
        if ($stmt->execute()) {
            header("Location: tareas.php");
            exit();
        } else {
            $mensaje = "Error: " . $stmt->error;
            $mensaje_tipo = 'error'; // Tipo de mensaje: error
        }
    } elseif (isset($_POST['eliminar_tarea'])) {
        $tarea_id = intval($_POST['tarea_id']);
        $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $tarea_id, $usuario_id);
        
        if ($stmt->execute()) {
            header("Location: tareas.php");
            exit();
        } else {
            $mensaje = "Error: " . $stmt->error;
            $mensaje_tipo = 'error'; // Tipo de mensaje: error
        }
    } elseif (isset($_POST['marcar_favorito'])) {
        $tarea_id = intval($_POST['tarea_id']);
        $stmt = $conn->prepare("UPDATE tareas SET favorito = NOT favorito WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $tarea_id, $usuario_id);
        
        if ($stmt->execute()) {
            header("Location: tareas.php");
            exit();
        } else {
            $mensaje = "Error: " . $stmt->error;
            $mensaje_tipo = 'error'; // Tipo de mensaje: error
        }
    }

     // Manejar la eliminación de la tarea
     if (isset($_POST['eliminar_tarea'])) {
        $tarea_id = $_POST['tarea_id'];
        $sql = "DELETE FROM tareas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $tarea_id);
        if ($stmt->execute()) {
            $mensaje = 'Tarea eliminada exitosamente.';
            $mensaje_tipo = 'confirm';
        } else {
            $mensaje = 'Error al eliminar la tarea.';
            $mensaje_tipo = 'error';
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cambiar_prioridad') {
    $tarea_id = $_POST['tarea_id'];
    $nueva_prioridad = $_POST['nueva_prioridad'];

    $stmt = $conn->prepare("UPDATE tareas SET prioridad = ? WHERE id = ?");
    $stmt->bind_param('si', $nueva_prioridad, $tarea_id);
    $stmt->execute();
    $stmt->close();
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar_fecha') {
    $tarea_id = $_POST['tarea_id'];
    $nueva_fecha = $_POST['nueva_fecha'];

    // Asegúrate de que estás usando consultas preparadas para evitar SQL Injection
    $update_query = "UPDATE tareas SET fecha_vencimiento = ? WHERE id = ?";
    $stmt = $conexion->prepare($update_query);
    $stmt->bind_param("si", $nueva_fecha, $tarea_id);
    $stmt->execute();

    // Respuesta opcional, por ejemplo, para verificar que se completó
    echo "Fecha actualizada exitosamente.";
    exit; // Asegúrate de salir para que no ejecute el resto del script
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aquí podrías tener código para agregar, eliminar tareas, etc.
    
    // Nuevo código para manejar el cambio de estado
    if (isset($_POST['actualizar_estado'])) {
        $tarea_id = intval($_POST['tarea_id']);
        $nuevo_estado = $_POST['nuevo_estado'];

        // Validar que el nuevo estado es válido
        if ($nuevo_estado != 'pendiente' && $nuevo_estado != 'completada') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Estado inválido']);
            exit();
        }

        // Actualizar el estado en la base de datos
        $stmt = $conn->prepare("UPDATE tareas SET estado = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("sii", $nuevo_estado, $tarea_id, $usuario_id);

        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => $stmt->error]);
        }
        exit(); // Terminar el script ya que es una solicitud AJAX
    }
}


if (isset($_GET['ordenar'])) {
    $criterio = $_GET['ordenar'];
    // Asegúrate de sanitizar el criterio para evitar inyecciones SQL
    $valid_criteria = ['id', 'descripcion', 'estado', 'prioridad', 'fecha_vencimiento'];
    if (in_array($criterio, $valid_criteria)) {
        $query = "SELECT * FROM tareas WHERE usuario_id = ? ORDER BY $criterio"; // Asegúrate de que esto es seguro
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $_SESSION['usuario_id']); // Bind para la seguridad
        $stmt->execute();
        $result = $stmt->get_result();
        $tareas = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($tareas); // Devuelve las tareas como JSON
        exit; // Asegúrate de salir después de enviar la respuesta
    }
}



// Consulta sin paginación
$stmt = $conn->prepare("SELECT id, descripcion, estado, prioridad, fecha_vencimiento, favorito FROM tareas WHERE usuario_id = ? ORDER BY $orden");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_tareas = $stmt->get_result();



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Tareas <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></title>
    <link rel="stylesheet" href="tareas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function marcarFavorito(tareaId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "tareas.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const boton = document.getElementById("favorito-" + tareaId);
                    const isFavorito = boton.classList.contains("favorito-activado");
                    boton.classList.toggle("favorito-activado");

                    if (isFavorito) {
                        boton.innerHTML = '<i class="far fa-heart"></i>'; // Corazón vacío
                    } else {
                        boton.innerHTML = '<i class="fas fa-heart"></i>'; // Corazón lleno
                    }
                }
            };
            xhr.send("tarea_id=" + tareaId + "&marcar_favorito=1");
        }

        function cambiarEstado(tareaId, estadoActual) {
            const nuevoEstado = estadoActual === 'pendiente' ? 'completada' : 'pendiente';
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "tareas.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const boton = document.getElementById("estado-" + tareaId);
                    boton.innerText = nuevoEstado.charAt(0).toUpperCase() + nuevoEstado.slice(1); // Cambia el texto del botón
                }
            };
            xhr.send("tarea_id=" + tareaId + "&nuevo_estado=" + nuevoEstado);
        }

        function eliminarTarea(tareaId) {
            if (confirm("¿Estás seguro de que quieres eliminar esta tarea?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "tareas.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const fila = document.getElementById("fila-" + tareaId);
                        fila.remove(); // Eliminar la fila de la tabla
                    }
                };
                xhr.send("tarea_id=" + tareaId + "&eliminar_tarea=1");
            }
        }

        function editarTarea(tareaId, descripcionActual) {
            const nuevaDescripcion = prompt("Edita la tarea:", descripcionActual);
            if (nuevaDescripcion !== null) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "tareas.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const fila = document.getElementById("descripcion-" + tareaId);
                        fila.innerText = nuevaDescripcion; // Actualiza la descripción de la tarea
                    }
                };
                xhr.send("tarea_id=" + tareaId + "&nueva_descripcion=" + encodeURIComponent(nuevaDescripcion));
            }
        }
    </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.update-status').click(function() {
            // Obtener los datos del botón
            var tareaId = $(this).data('id');
            var nuevoEstado = $(this).data('status');
            
            // Enviar la solicitud AJAX
            $.ajax({
                url: 'actualizar_estado.php', // Asegúrate de que este sea el archivo PHP correcto
                type: 'POST',
                data: {
                    id: tareaId,
                    estado: nuevoEstado
                },
                success: function(response) {
                    // Maneja la respuesta del servidor (actualización exitosa)
                    if (response.success) {
                        alert('Estado actualizado correctamente');
                        // Opcionalmente recarga la parte del contenido o actualiza el botón
                        location.reload(); // Esto recarga la página solo si es necesario
                    } else {
                        alert('Error al actualizar el estado');
                    }
                },
                error: function(xhr, status, error) {
                    // Maneja el error si la solicitud AJAX falla
                    alert('Error de servidor. Intenta de nuevo más tarde.');
                }
            });
        });
    });




</script>
    <style>
        /* Importar fuente Open Sans desde Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');

/* Estilo general para la página */
body {
    font-family: 'Open Sans', sans-serif; /* Aplicar la fuente Open Sans para un diseño moderno */
    background-color: white; /* Fondo blanco para una apariencia limpia */
    margin: 0; /* Eliminar márgenes por defecto */
    padding: 0; /* Eliminar relleno por defecto */
}

/* Contenedor principal que incluye el menú y las tareas */
.container {
    display: flex; /* Usar flexbox para alinear los elementos */
    justify-content: center; /* Centrar el contenido horizontalmente */
    margin-left: 80px; /* Añadir margen izquierdo de 80px */
}

/* Contenedor de tareas */
.tareas-container {
    width: 91%; /* Cambiar a un ancho más pequeño */
    padding: 20px; /* Espaciado interno reducido */
    background-color: white; /* Fondo blanco */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra sutil para profundidad */
    flex-grow: 1; /* Permitir que el contenedor crezca si es necesario */
    margin-left: 81px; /* Ajustar el margen izquierdo para moverlo más a la izquierda */
    margin-right: 0; /* Eliminar margen derecho */
    margin-top: 20px; /* Añadir margen superior para espacio arriba */
}

/* Estilo para la tabla de tareas */
table {
    width: 100%; /* Ancho completo del contenedor */
    border-collapse: separate; /* Mantiene los bordes separados para personalizar */
    border-spacing: 0; /* Elimina el espacio entre celdas */
    margin-bottom: 20px; /* Margen inferior */
}

thead {
    background-color: white; /* Fondo blanco para el encabezado */
}

th {
    color: black; /* Color del texto en el encabezado */
    padding: 7px; /* Espaciado interno */
    border-top: 2px solid #f3f3f3; /* Borde superior azul */
    border-bottom: 2px solid #f3f3f3; /* Borde inferior azul */
    border-left: 2px solid #ffffff; /* Borde izquierdo verde */
    border-right: 2px solid #ffffff; /* Borde derecho verde */
    font-size: 14px; /* Para aumentar el tamaño a 18 píxeles */
    text-align: center; /* Alinea el texto del encabezado en el centro */
}

td {
    padding: 0px; /* Espaciado interno */
    font-size: 14px; /* Tamaño de fuente */
    border-top: 2px solid #ffffff; /* Borde superior azul */
    border-bottom: 2px solid #f3f3f3; /* Borde inferior azul */
    border-left: 2px solid #ffffff; /* Borde izquierdo verde */
    border-right: 2px solid #ffffff; /* Borde derecho verde */
    overflow: hidden; /* Evita el desbordamiento del contenido */
    white-space: nowrap; /* Evita que el texto se divida en varias líneas */
    text-overflow: ellipsis; /* Agrega puntos suspensivos si el texto es demasiado largo */
    text-align: center; /* Centra el texto por defecto */
}


/* Ajustes de alineación y tamaño para cada columna */
th:nth-child(3) {
    text-align: center; /* Encabezado de la columna 3 centrado */
}

td:nth-child(3) {
    text-align: left; /* Descripción de tarea alineada a la izquierda */
}

/* Ajustes de tamaño para cada columna */
th:nth-child(1), td:nth-child(1) { /* ID */
    width: 30px;
}

th:nth-child(2), td:nth-child(2) { /* Favorito */
    width: 60px;
}

th:nth-child(3), td:nth-child(3) { /* Tarea */
    width: 300px;
}

th:nth-child(4), td:nth-child(4) { /* Estado */
    width: 120px;
}

th:nth-child(5), td:nth-child(5) { /* Prioridad */
    width: 120px;
}

th:nth-child(6), td:nth-child(6) { /* Fecha de Vencimiento */
    width: 120px;
}

th:nth-child(7), td:nth-child(7) { /* Acción */
    width: 120px;
}

tr:nth-child(even) {
    background-color: #ffffff; /* Fondo blanco para filas pares */
}

tr:hover {
    background-color: #ffffff; /* Fondo blanco al pasar el ratón */
}

        /* Estilo específico para el botón de favorito */
        .favorito {
            background-color: white; /* Fondo blanco cuando está desactivado */
            border: none;
            cursor: pointer;
            font-size: 14px; /* Tamaño del ícono */
            color: #e63946; /* Color del corazón */
            transition: color 0.3s, background-color 0.3s; /* Transiciones suaves */
            position: relative; /* Para el pseudo-elemento */
            border-radius: 0%; /* Bordes redondeados */
            padding: 10px; /* Espaciado interno */
        }

        .favorito-activado { /* Cuando el favorito está activado */
            color: #ff6b6b; /* Color del corazón lleno */
        }

        /* Estilo para el botón desactivado */
        .favorito:not(.favorito-activado):hover {
            background-color: rgba(255, 255, 255, 0.7); /* Efecto de hover */
        }

/* Estilo para el selector de estado */
.status {
    padding: 5px 24px; /* Espaciado interno similar al botón */
    font-size: 14px; /* Tamaño de fuente */
    border-radius: 4px; /* Bordes redondeados */
    border: none; /* Sin borde */
    cursor: pointer; /* Cambia el cursor al pasar sobre el selector */
    text-transform: capitalize; /* Capitalizar la primera letra */
    appearance: none; /* Ocultar el estilo por defecto del navegador */
    -webkit-appearance: none;
    -moz-appearance: none;
    text-align: center; /* Centrar texto */
}

/* Personalización de la flecha del selector */
.status {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 6"><polygon points="0,0 5,6 10,0" fill="%23000000"/></svg>');
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 7px 7px;
}

/* Estilo para el estado 'pendiente' */
.status.pendiente {
    background-color: #fffd8e; /* Fondo amarillo */
    color: rgb(0, 0, 0); /* Texto negro */
}

.status.pendiente:hover {
    background-color: #fff9b5; /* Amarillo más claro al pasar el ratón */
}

/* Estilo para el estado 'completada' */
.status.completada {
    background-color: #bfffce; /* Fondo verde */
    color: rgb(0, 0, 0); /* Texto negro */
}

.status.completada:hover {
    background-color: #d8ffe0; /* Verde más claro al pasar el ratón */
}

/* Para centrar el texto verticalmente */
.status option {
    text-align: center; /* Alinear texto del option */
}

/* Estilo para el selector de prioridad */
.prioridad {
    padding: 5px 29px; /* Espaciado interno similar al botón */
    font-size: 14px; /* Tamaño de fuente */
    border-radius: 4px; /* Bordes redondeados */
    border: none; /* Sin borde */
    cursor: pointer; /* Cambia el cursor al pasar sobre el selector */
    text-transform: capitalize; /* Capitalizar la primera letra */
    appearance: none; /* Ocultar el estilo por defecto del navegador */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 6"><polygon points="0,0 5,6 10,0" fill="%23000000"/></svg>'); /* Flecha negra */
    background-repeat: no-repeat;
    background-position: right 10px center; /* Posición de la flecha */
    background-size: 7px 7px; /* Tamaño de la flecha */
    text-align: center; /* Centrar texto */
}

/* Estilo para las prioridades con colores brillantes */
.prioridad.baja {
    background-color: #f2bfff; /* Coral brillante */
}

.prioridad.media {
    background-color:#ffbfef; /* Tomate */
}

.prioridad.alta {
    background-color: #ffbfd4; /* Rosa profundo */
}

/* Cambiar el color de la flecha al pasar el mouse */
.prioridad:hover {
    background-color: rgba(255, 255, 255, 0.8); /* Fondo más claro al pasar el ratón */
}


/* Estilo general para el campo de fecha de vencimiento */
input[type="date"] {
    padding: 10px; /* Espaciado interno */
    font-size: 14px; /* Tamaño de fuente */
    border: 0px solid #ccc; /* Bordes grises */
    border-radius: 4px; /* Bordes redondeados */
    width: 100%; /* Ancho completo */
    box-sizing: border-box; /* Incluye el padding y el borde en el ancho total */
    transition: border-color 0.3s ease; /* Transición suave para el color del borde */
}



/* Estilo para el campo de fecha al enfocar */
input[type="date"]:focus {
    border-color: #007bff; /* Color del borde azul al enfocar */
    outline: none; /* Eliminar el contorno por defecto */
}

/* Estilo para el campo de fecha deshabilitado */
input[type="date"]:disabled {
    background-color: #f5f5f5; /* Fondo gris claro */
    cursor: not-allowed; /* Cursor de no permitido */
}

/* Estilo para el texto del campo de fecha */
input[type="date"]::-webkit-inner-spin-button {
    -webkit-appearance: none; /* Eliminar el botón de incremento y decremento en navegadores webkit */
    margin: 0; /* Margen cero */
}

/* Estilo para el texto del campo de fecha en navegadores Firefox */
input[type="date"] {
    appearance: none; /* Ocultar el estilo por defecto del navegador */
    -moz-appearance: textfield; /* Estilo para Firefox */
}

/* Estilo adicional para las etiquetas */
label {
    display: block; /* Hacer la etiqueta un bloque */
    margin-bottom: 0px; /* Espaciado inferior */
    font-weight: bold; /* Texto en negrita */
    margin-top: 0px; /* Espaciado superior para la etiqueta */
}


.edit-task {
    background-color: #c1cfff; /* Fondo azul */
    color: rgb(0, 0, 0); /* Texto blanco */
    border: none; /* Sin borde */
    padding: 5px 17px; /* Espaciado interno */
    font-size: 14px; /* Tamaño de fuente */
    border-radius: 4px; /* Bordes redondeados */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
}

.edit-task:hover {
    background-color: #ffffff; /* Azul más oscuro al pasar el ratón */
}

.remove-task {
    background-color: #ffb3b3; /* Fondo rojo pastel */
    color: rgb(0, 0, 0); /* Texto blanco */
    border: none; /* Sin borde */
    padding: 5px 10px; /* Espaciado interno */
    font-size: 14px; /* Tamaño de fuente */
    border-radius: 4px; /* Bordes redondeados */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
}

.remove-task:hover {
    background-color: #c82333; /* Rojo más oscuro al pasar el ratón */
}



/* Estilo para añadir tarea */

.add-task-container {
    display: flex;
    align-items: center; /* Alinea verticalmente el contenido */
    margin-bottom: 15px; /* Espacio entre el formulario y la tabla */
}

.add-task {
    display: flex;
    align-items: center;
    margin-top: -5px; /* Espaciado superior */
    background-color: white; /* Fondo blanco para el formulario */
    padding: 10px; /* Espaciado interno para que no esté pegado */
    border-radius: 4px; /* Bordes redondeados */
}

.add-task input[type="text"] {
    width: 70%; /* Ancho ajustado al 20% */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;                                                                                                                                                 
    margin-right: 10px; /* Espacio entre el campo de texto y el botón */
    background-color: #ffffff; /* Fondo claro para el campo de entrada */
}

.add-task button {
    background-color: #9beae1; /* Soft pastel turquoise */
    border: none;
    color: black; /* Texto negro */
    padding: 5px 15px; /* Espaciado interno */
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px; /* Tamaño de fuente reducido */
    transition: background-color 0.3s; /* Transición suave al cambiar color */
}

.add-task button:hover {
    background-color: #ffffff; /* Fondo blanco al pasar el mouse */
}

#ordenar {
    padding: 3.5px; /* Espaciado interno del dropdown */
    border: 1px solid #ccc; /* Borde para el dropdown */
    border-radius: 4px; /* Bordes redondeados */
    background-color: white; /* Color de fondo */
    margin-top: -60px; /* Ajusta la posición vertical hacia arriba */
    line-height: 10; /* Ajusta la altura de línea para centrar verticalmente */
}



    </style>
</head>
<body id="tareas-page">
    <div class="tareas-container">
        <h2><i class="fas fa-edit"></i> Listado de Tareas de <span id="nombre-usuario"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span></h2>


        
        <!-- Formulario para añadir nueva tarea y selector de ordenamiento -->
<div class="add-task-container">
            <form action="tareas.php" method="POST" class="add-task">
                <input type="text" name="descripcion" placeholder="Nueva tarea" required>
                <button type="submit" name="nueva_tarea">Añadir</button>
            </form>
            <form class="sort-form" action="tareas.php" method="GET">
    <label for="ordenar"></label>
    <select name="ordenar" id="ordenar" onchange="this.form.submit()" style="padding: 3.5px; border: 1px solid #ccc; border-radius: 4px; background-color: white; margin-top: -6px; line-height: 1.5;">
        <option value="id" <?php if ($orden == 'id') echo 'selected'; ?>>ID</option>
        <option value="descripcion" <?php if ($orden == 'descripcion') echo 'selected'; ?>>Tareas</option>
        <option value="estado" <?php if ($orden == 'estado') echo 'selected'; ?>>Estado</option>
        <option value="prioridad" <?php if ($orden == 'prioridad') echo 'selected'; ?>>Prioridad</option>
        <option value="fecha_vencimiento" <?php if ($orden == 'fecha_vencimiento') echo 'selected'; ?>>Fecha de Vencimiento</option>
    </select>
</form>


        </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Favorito</th>
                    <th>Tarea</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result_tareas->fetch_assoc()): ?>
    <tr id="fila-<?php echo htmlspecialchars($row['id']); ?>">
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td>
            <button id="favorito-<?php echo htmlspecialchars($row['id']); ?>" class="favorito <?php echo $row['favorito'] ? 'favorito-activado' : ''; ?>" onclick="marcarFavorito(<?php echo htmlspecialchars($row['id']); ?>)">
                <i class="<?php echo $row['favorito'] ? 'fas fa-heart' : 'far fa-heart'; ?>"></i> <!-- Corazón lleno o vacío -->
            </button>
        </td>
        <td id="descripcion-<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['descripcion']); ?></td>
        

        
        <td>
            <select class="status <?php echo htmlspecialchars($row['estado']); ?>" onchange="actualizarEstado(<?php echo htmlspecialchars($row['id']); ?>, this.value); actualizarColor(this)">
                <option value="pendiente" <?php if ($row['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="completada" <?php if ($row['estado'] == 'completada') echo 'selected'; ?>>Completada</option>
            </select>
        </td>
        <td>
            <select class="prioridad" data-tarea-id="<?php echo htmlspecialchars($row['id']); ?>" onchange="cambiarPrioridad(<?php echo htmlspecialchars($row['id']); ?>, this.value)">
                <option value="baja" <?php if ($row['prioridad'] == 'baja') echo 'selected'; ?>>Baja</option>
                <option value="media" <?php if ($row['prioridad'] == 'media') echo 'selected'; ?>>Media</option>
                <option value="alta" <?php if ($row['prioridad'] == 'alta') echo 'selected'; ?>>Alta</option>
            </select>
        </td>
        <td>
        <input type="date" id="fecha_vencimiento_<?php echo htmlspecialchars($row['id']); ?>" name="fecha_vencimiento" value="<?php echo htmlspecialchars($row['fecha_vencimiento']); ?>" onchange="actualizarFecha(<?php echo htmlspecialchars($row['id']); ?>, this.value)" required>
        </td>
        <td>
            <div>
                <button class="edit-task" onclick="editarTarea(<?php echo htmlspecialchars($row['id']); ?>, '<?php echo htmlspecialchars($row['descripcion']); ?>')">Editar</button>
                <button class="remove-task" onclick="eliminarTarea(<?php echo htmlspecialchars($row['id']); ?>)">Eliminar</button>
            </div>
        </td>
    </tr>
<?php endwhile; ?>

            </tbody>
        </table>

       
        <?php if ($mensaje): ?>
            <div class="alert <?php echo $mensaje_tipo; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
                <?php if ($mensaje_tipo === 'confirm'): ?>
                    <form action="tareas.php" method="POST" style="display:inline;">
                        <input type="hidden" name="confirmar_creacion" value="1">
                        <button type="submit">Confirmar</button>
                    </form>
                    <form action="tareas.php" method="POST" style="display:inline;">
                        <input type="hidden" name="cancelar_creacion" value="1">
                        <button type="submit">Cancelar</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>


    <script>
function actualizarEstado(tareaId, nuevoEstado) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "tareas.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.success) {
                console.log('Estado actualizado correctamente');
            } else {
                alert('Error al actualizar el estado: ' + respuesta.error);
            }
        } else {
            alert('Error en la solicitud');
        }
    };
    xhr.send("tarea_id=" + tareaId + "&nuevo_estado=" + nuevoEstado + "&actualizar_estado=1");
}

function actualizarColor(select) {
    // Remover clases existentes
    select.classList.remove('pendiente', 'completada');

    // Obtener el valor actual del selector
    const estado = select.value;

    // Agregar la clase correspondiente según el estado
    select.classList.add(estado);
}


function cambiarPrioridad(tareaId, nuevaPrioridad) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "tareas.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (this.status === 200) {
            console.log('Prioridad actualizada');
            // Cambiar el color del select dependiendo de la nueva prioridad
            const select = document.querySelector(`select[data-tarea-id='${tareaId}']`);
            if (select) {
                if (nuevaPrioridad === 'baja') {
                    select.style.backgroundColor = '#f2bfff'; // Lila pastel
        } else if (prioridad === 'media') {
            select.style.backgroundColor = '#ffbfef'; // Rosa pastel
        } else if (prioridad === 'alta') {
            select.style.backgroundColor = '#ffbfd4'; // Durazno pastel
        }
            }
        }
    };
    xhr.send("accion=cambiar_prioridad&tarea_id=" + tareaId + "&nueva_prioridad=" + nuevaPrioridad);
}

window.onload = function() {
    document.querySelectorAll('select.prioridad').forEach(function(select) {
        const prioridad = select.value;
        if (prioridad === 'baja') {
            select.style.backgroundColor = '#f2bfff'; // Lila pastel
        } else if (prioridad === 'media') {
            select.style.backgroundColor = '#ffbfef'; // Rosa pastel
        } else if (prioridad === 'alta') {
            select.style.backgroundColor = '#ffbfd4'; // Durazno pastel
        }
    });
};

function actualizarFecha(tareaId, nuevaFecha) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "tareas.php", true); // Asegúrate de que esta ruta sea correcta.
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onload = function () {
        if (this.status === 200) {
            console.log('Fecha de vencimiento actualizada:', this.responseText);
            // Opcional: Actualizar la interfaz o dar un mensaje de éxito aquí
        } else {
            console.error('Error al actualizar la fecha de vencimiento:', this.status, this.statusText);
        }
    };
}

function ordenarTareas(criterio) {
    fetch('tareas.php?ordenar=' + criterio)
        .then(response => response.json())
        .then(data => {
            const tareasContainer = document.getElementById('tareas-container');
            tareasContainer.innerHTML = ''; // Limpia las tareas actuales

            data.forEach(tarea => {
                const tareaElement = document.createElement('div');
                tareaElement.innerHTML = `
                    <p>ID: ${tarea.id}, Descripción: ${tarea.descripcion}, Estado: ${tarea.estado}, Prioridad: ${tarea.prioridad}, Fecha Vencimiento: ${tarea.fecha_vencimiento}</p>
                `;
                tareasContainer.appendChild(tareaElement);
            });
        })
        .catch(error => console.error('Error al ordenar tareas:', error));
}



</script>


</body>
</html>

<?php
$conn->close();
?>