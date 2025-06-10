<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php'; // Incluir la configuración de conexión a la base de datos

$media_id = $_GET['photo_id'] ?? $_GET['video_id'] ?? null;
$media_type = isset($_GET['photo_id']) ? 'photo' : (isset($_GET['video_id']) ? 'video' : null);

if (!$media_id || !$media_type) {
    echo "ID de medio no proporcionado.";
    exit();
}

$media = null;
$comments = [];
try {
    // Obtener información del medio (foto o video)
    if ($media_type === 'photo') {
        $stmt = $pdo->prepare("SELECT id, file_path, title FROM photos WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("SELECT id, file_path, title FROM videos WHERE id = ?");
    }
    $stmt->execute([$media_id]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$media) {
        echo "Medio no encontrado.";
        exit();
    }

    // Obtener comentarios del medio
    if ($media_type === 'photo') {
        $stmt = $pdo->prepare("SELECT c.id, c.comment_text, c.user_id, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.photo_id = ? ORDER BY c.created_at DESC");
     } else {
         $stmt = $pdo->prepare("SELECT c.id, c.comment AS comment_text, c.user_id, u.username FROM video_comments c JOIN users u ON c.user_id = u.id WHERE c.video_id = ? ORDER BY c.created_at DESC");
    }
    $stmt->execute([$media_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al cargar los comentarios: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Comentarios</title>
    <link rel="stylesheet" href="css/photos.css">
</head>
<body>
    <div class="container">
        <h1>Comentarios del <?php echo ($media_type === 'photo' ? 'Foto' : 'Video'); ?></h1>
        <?php if ($media_type === 'photo'): ?>
            <img src="<?php echo htmlspecialchars($media['file_path']); ?>" alt="<?php echo htmlspecialchars($media['title'] ?? 'Foto'); ?>" style="max-width: 300px; height: auto;">
        <?php else: ?>
            <video controls style="max-width: 300px; height: auto;">
                <source src="<?php echo htmlspecialchars($media['file_path']); ?>" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
        <?php endif; ?>
        
        <?php if (empty($comments)): ?>
            <p>No hay comentarios para este <?php echo ($media_type === 'photo' ? 'foto' : 'video'); ?> aún.</p>
        <?php else: ?>
            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item">
                        <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                        <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                        <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                            <div class="comment-actions">
                                <a href="edit_comment.php?comment_id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-info">Editar</a>
                                <a href="<?php echo ($media_type === 'photo' ? 'delete_comment.php' : 'delete_video_comment.php'); ?>?comment_id=<?php echo $comment['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este comentario?');" class="btn btn-sm btn-danger">Eliminar</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p><a href="../index.php" class="btn btn-primary mt-3">Volver al Inicio</a></p>
    </div>
</body>
</html>