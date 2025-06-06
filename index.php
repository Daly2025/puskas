<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puskas</title>
    <link rel="stylesheet" href="public/css/index.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header-right">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="public/logout.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Bienvenida a Cas'Pilar</h1>
        <p>Aquí podrás subir y ver fotos y videos.</p>

        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="public/photos.php">Galería de Fotos</a></li>
                <li><a href="public/videos.php">Galería de Videos</a></li>
            </ul>
        </nav>

        <?php if (isset($_SESSION['username'])): ?>
            <div class="upload-buttons">
                <a href="public/upload_photo.php" class="button"><i class="fas fa-upload"></i> Subir Foto</a>
                <a href="public/upload_video.php" class="button"><i class="fas fa-upload"></i> Subir Video</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
