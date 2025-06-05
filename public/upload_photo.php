<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';

// Lógica para manejar la subida de fotos (se implementará más adelante)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = 'Funcionalidad de subida de fotos en desarrollo.';
    // Aquí iría la lógica para procesar la subida de la foto
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