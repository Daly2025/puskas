<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php'; // Tu conexión PDO

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "uploads/";

    // Crear carpeta si no existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $videoFile = $_FILES["video_file"];
    $target_file = $target_dir . basename($videoFile["name"]);
    $uploadOk = 1;

    // Obtener extensión en minúsculas
    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validar que es video real (puedes hacer validaciones más específicas)
    $allowedTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'];
    if (!in_array($videoFileType, $allowedTypes)) {
        $message = "Solo se permiten videos con formatos: " . implode(", ", $allowedTypes);
        $uploadOk = 0;
    }

    // Tamaño máximo 50MB (ajustar según necesidad)
    if ($videoFile["size"] > 50 * 1024 * 1024) {
        $message = "El archivo es demasiado grande. Máximo 50MB.";
        $uploadOk = 0;
    }

    // Comprobar si archivo ya existe
    if (file_exists($target_file)) {
        $message = "El archivo ya existe.";
        $uploadOk = 0;
    }

    if ($uploadOk === 1) {
        if (move_uploaded_file($videoFile["tmp_name"], $target_file)) {
            $video_title = $_POST['video_title'] ?? null;
            $user_id = $_SESSION['user_id'];
            $video_path = $target_file;

            try {
                $stmt = $pdo->prepare("INSERT INTO videos (user_id, title, file_path) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $video_title, $video_path]);
                $message = "El video " . htmlspecialchars(basename($videoFile["name"])) . " ha sido subido correctamente.";
            } catch (PDOException $e) {
                $message = "Error al guardar en la base de datos: " . $e->getMessage();
            }
        } else {
            $message = "Error al subir el archivo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Subir Video</title>
    <link rel="stylesheet" href="css/upload.css" />
</head>
<body>
    <div class="container">
        <h2 class="form-title">Subir Nuevo Video</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="upload_video.php" method="post" enctype="multipart/form-data" class="upload-form">
            <div class="form-group">
                <label for="video_file">Seleccionar Video:</label>
                <input type="file" id="video_file" name="video_file" accept="video/*" required>
            </div>
            <div class="form-group">
                <label for="video_title">Título (opcional):</label>
                <input type="text" id="video_title" name="video_title">
            </div>
            <button type="submit">Subir Video</button>
        </form>
        <p class="link-text"><a href="../index.php">Volver al Inicio</a></p>
    </div>
</body>
</html>
