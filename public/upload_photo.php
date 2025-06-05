<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Incluir el archivo de configuración para la conexión a la base de datos
require_once '../includes/config.php';

$message = '';

// Lógica para manejar la subida de fotos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Directorio donde se guardarán las fotos
    $target_dir = "uploads/";
    // Crear el directorio si no existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["photo_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Comprobar si el archivo de imagen es una imagen real o una imagen falsa
    $check = getimagesize($_FILES["photo_file"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Comprobar si el archivo ya existe
    if (file_exists($target_file)) {
        $message = "Lo siento, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Comprobar el tamaño del archivo (ej. 5MB máximo)
    if ($_FILES["photo_file"]["size"] > 5000000) {
        $message = "Lo siento, tu archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $message = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Comprobar si $uploadOk es 0 por un error
    if ($uploadOk == 0) {
        $message = "Lo siento, tu archivo no fue subido.";
    // Si todo está bien, intentar subir el archivo
    } else {
        if (move_uploaded_file($_FILES["photo_file"]["tmp_name"], $target_file)) {
            $photo_title = $_POST['photo_title'] ?? null;
            $user_id = $_SESSION['user_id'];
            $photo_path = $target_file;

            try {
                $stmt = $pdo->prepare("INSERT INTO photos (user_id, title, file_path) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $photo_title, $photo_path]);
                $message = "La foto ". htmlspecialchars( basename( $_FILES["photo_file"]["name"])) . " ha sido subida y guardada en la base de datos.";
            } catch (PDOException $e) {
                $message = "Error al guardar en la base de datos: " . $e->getMessage();
            }
        } else {
            $message = "Lo siento, hubo un error al subir tu archivo.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Foto</title>
    <link rel="stylesheet" href="login.css"> <!-- Puedes usar un CSS existente o crear uno nuevo -->
</head>
<body>
    <div class="container">
        <h2>Subir Nueva Foto</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="upload_photo.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="photo_file">Seleccionar Foto:</label>
                <input type="file" id="photo_file" name="photo_file" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="photo_title">Título (opcional):</label>
                <input type="text" id="photo_title" name="photo_title">
            </div>
            <button type="submit">Subir Foto</button>
        </form>
        <p class="link-text"><a href="../index.php">Volver al Inicio</a></p>
    </div>
</body>
</html>