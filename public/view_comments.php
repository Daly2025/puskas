<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php'; // Incluir la configuración de conexión a la base de datos

$photo_id = $_GET['photo_id'] ?? null;

if (!$photo_id) {
    echo "ID de foto no proporcionado.";
    exit();
}

$photo = null;
$comments = [];
try {
    // Obtener información de la foto
    $stmt = $pdo->prepare("SELECT id, file_path, title FROM photos WHERE id = ? AND user_id = ?");
    $stmt->execute([$photo_id, $_SESSION['user_id']]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$photo) {
        echo "Foto no encontrada o no tienes permiso para verla.";
        exit();
    }

    // Obtener comentarios de la foto
    $stmt = $pdo->prepare("SELECT c.id, c.comment_text, c.user_id, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.media_id = ? AND c.media_type = 'photo' ORDER BY c.created_at DESC");
    $stmt->execute([$photo_id]);
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
        <h1>Comentarios de la Foto</h1>
        <img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title'] ?? 'Foto'); ?>" style="max-width: 300px; height: auto;">
        
        <?php if (empty($comments)): ?>
            <p>No hay comentarios para esta foto aún.</p>
        <?php else: ?>
            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item">
                        <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                        <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                        <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                            <div class="comment-actions">
                                <a href="edit_comment.php?comment_id=<?php echo $comment['id']; ?>">Editar</a>
                                <a href="delete_comment.php?comment_id=<?php echo $comment['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este comentario?');">Eliminar</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p><a href="photos.php">Volver a la Galería</a></p>
    </div>
</body>
</html>