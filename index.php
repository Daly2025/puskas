<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puskas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container-fluid bg-light py-3">
        <div class="d-flex justify-content-end align-items-center">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="me-3">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="public/logout.php" class="btn btn-outline-secondary">Cerrar Sesión</a>
            <?php else: ?>
                <a href="public/login.php" class="btn btn-primary">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Bienvenida a Cas'Pilar</h1>
        <p class="text-center lead">Aquí podrás subir y ver fotos y videos.</p>

        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="public/photos.php">Galería de Fotos</a></li>
                        <li class="nav-item"><a class="nav-link" href="public/videos.php">Galería de Videos</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php if (isset($_SESSION['username'])): ?>
            <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                <a href="public/upload_photo.php" class="btn btn-success btn-lg"><i class="fas fa-upload"></i> Subir Foto</a>
                <a href="public/upload_video.php" class="btn btn-info btn-lg"><i class="fas fa-upload"></i> Subir Video</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
