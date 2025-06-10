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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Galería de Videos</h1>

        <?php if (empty($videos)): ?>
            <div class="alert alert-info text-center" role="alert">
                Aún no has subido ningún video.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($videos as $video): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="embed-responsive embed-responsive-16by9">
                                <video controls class="embed-responsive-item w-100">
                                    <source src="<?php echo htmlspecialchars($video['file_path']); ?>" type="video/mp4">
                                    Tu navegador no soporta la etiqueta de video.
                                </video>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($video['title'])): ?>
                                    <h5 class="card-title"><?php echo htmlspecialchars($video['title']); ?></h5>
                                <?php endif; ?>
                                <div class="d-grid gap-2">
                                    <a href="comment.php?video_id=<?php echo $video['id']; ?>" class="btn btn-primary">Comentar</a>
                                    <a href="view_comments.php?video_id=<?php echo $video['id']; ?>" class="btn btn-info">Ver comentarios</a>
                                    <a href="edit_video.php?video_id=<?php echo $video['id']; ?>" class="btn btn-warning">Editar</a>
                                    <a href="delete_video.php?video_id=<?php echo $video['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este video?');">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
