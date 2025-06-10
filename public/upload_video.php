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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-image: url('uploads/otrorock.jpeg'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Subir Nuevo Video</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="upload_video.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="video_file" class="form-label">Seleccionar Video:</label>
                <input type="file" class="form-control" id="video_file" name="video_file" accept="video/*" required>
            </div>
            <div class="mb-3">
                <label for="video_title" class="form-label">Título (opcional):</label>
                <input type="text" class="form-control" id="video_title" name="video_title">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Subir Video</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
