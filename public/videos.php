<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$videos = [];
try {
    $stmt = $pdo->prepare("SELECT id, title, file_path FROM videos WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al cargar los videos: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Videos</title>
    <link rel="stylesheet" href="css/videos.css" />
</head>
<body>
    <h1>Galería de Videos</h1>

    <?php if (empty($videos)): ?>
        <p>Aún no has subido ningún video.</p>
    <?php else: ?>
        <div class="video-gallery">
            <?php foreach ($videos as $video): ?>
                <div class="video-item">
                    <video controls>
                        <source src="<?php echo htmlspecialchars($video['file_path']); ?>" type="video/mp4">
                        Tu navegador no soporta la etiqueta de video.
                    </video>
                    <?php if (!empty($video['title'])): ?>
                        <p><?php echo htmlspecialchars($video['title']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <p><a href="index.php">Volver al Inicio</a></p>
</body>
</html>
