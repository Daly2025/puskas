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
    // Directorio donde se guardarán las fotos, dentro de public/
    $target_dir = "uploads/";

    // Crear el directorio si no existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Asegurarse que el nombre del archivo sea único para evitar sobreescrituras
    $filename = basename($_FILES["photo_file"]["name"]);
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $file_base = pathinfo($filename, PATHINFO_FILENAME);
    
    $counter = 0;
    $target_file = $target_dir . $filename;

    while (file_exists($target_file)) {
        $counter++;
        $filename = $file_base . "_" . $counter . "." . $file_ext;
        $target_file = $target_dir . $filename;
    }

    $uploadOk = 1;
    $imageFileType = $file_ext;

    // Comprobar si el archivo de imagen es una imagen real o una imagen falsa
    $check = getimagesize($_FILES["photo_file"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "El archivo no es una imagen.";
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
        $message = $message ?: "Lo siento, tu archivo no fue subido.";
    // Si todo está bien, intentar subir el archivo
    } else {
        if (move_uploaded_file($_FILES["photo_file"]["tmp_name"], $target_file)) {
            $photo_title = $_POST['photo_title'] ?? null;
            $user_id = $_SESSION['user_id'];

            // Guardar ruta relativa desde 'public/' para que se muestre bien en la galería
            $photo_path = $target_file; // esto es 'uploads/archivo.ext'

            // Redimensionar la imagen después de subirla
            resizeImage($target_file, 800, 800); // Redimensiona a un máximo de 800px de ancho o alto

            try {
                $stmt = $pdo->prepare("INSERT INTO photos (user_id, title, file_path) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $photo_title, $photo_path]);
                $message = "La foto ". htmlspecialchars($filename) . " ha sido subida y guardada en la base de datos.";
            } catch (PDOException $e) {
                $message = "Error al guardar en la base de datos: " . $e->getMessage();
                // Opcional: eliminar el archivo si falla la BD
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }
        } else {
            $message = "Lo siento, hubo un error al subir tu archivo.";
        }
    }
}

// Función para redimensionar la imagen
function resizeImage($file, $max_width, $max_height, $quality = 80) {
    list($orig_width, $orig_height) = getimagesize($file);
    $width = $orig_width;
    $height = $orig_height;

    // Calcular nuevas dimensiones manteniendo la relación de aspecto
    if ($width > $max_width || $height > $max_height) {
        $ratio = $orig_width / $orig_height;
        if ($ratio > 1) { // Horizontal
            $width = $max_width;
            $height = $max_width / $ratio;
        } else { // Vertical o Cuadrada
            $height = $max_height;
            $width = $max_height * $ratio;
        }
    }

    $image_p = imagecreatetruecolor($width, $height);
    $image = imagecreatefromjpeg($file);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

    // Guardar la imagen redimensionada
    imagejpeg($image_p, $file, $quality);
    imagedestroy($image_p);
    imagedestroy($image);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Subir Foto</title>
    <link rel="stylesheet" href="css/upload.css" />
</head>
<body>
    <div class="container">
        <h2 class="form-title">Subir Nueva Foto</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="upload_photo.php" method="post" enctype="multipart/form-data" class="upload-form">
            <div class="form-group">
                <label for="photo_file">Seleccionar Foto:</label>
                <input type="file" id="photo_file" name="photo_file" accept="image/*" required />
            </div>
            <div class="form-group">
                <label for="photo_title">Título (opcional):</label>
                <input type="text" id="photo_title" name="photo_title" />
            </div>
            <button type="submit">Subir Foto</button>
        </form>
        <p class="link-text"><a href="../index.php"><img src="images/home_button.png" alt="Volver al Inicio" style="width:50px;height:50px;"></a></p>
    </div>
</body>
</html>
