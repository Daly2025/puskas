<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';

// Lógica para manejar la subida de videos (se implementará más adelante)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = 'Funcionalidad de subida de videos en desarrollo.';
    // Aquí iría la lógica para procesar la subida del video
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Video</title>
    <link rel="stylesheet" href="login.css"> <!-- Puedes usar un CSS existente o crear uno nuevo -->
</head>
<body>
    <div class="container">
        <h2>Subir Nuevo Video</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="upload_video.php" method="post" enctype="multipart/form-data">
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