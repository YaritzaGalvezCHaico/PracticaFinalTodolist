<?php
session_start(); // Asegúrate de que las sesiones están iniciadas
include 'conexion.php'; // Incluye tu archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profileImage']['tmp_name'];
        $fileName = $_FILES['profileImage']['name'];
        $fileSize = $_FILES['profileImage']['size'];
        $fileType = $_FILES['profileImage']['type'];
        
        // Define la carpeta donde guardarás las imágenes
        $uploadFileDir = './uploaded_images/';
        $dest_path = $uploadFileDir . basename($fileName);

        // Mueve el archivo a la carpeta deseada
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Guardar la ruta en la base de datos
            $userId = $_SESSION['user_id']; // Asegúrate de tener el ID del usuario en la sesión
            $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
            $stmt->bind_param("si", $dest_path, $userId);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'filePath' => $dest_path]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la base de datos.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al mover el archivo.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió ninguna imagen.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido.']);
}
?>

