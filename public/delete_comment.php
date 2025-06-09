<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$comment_id = $_GET['comment_id'] ?? null;

if (!$comment_id) {
    echo "ID de comentario no proporcionado.";
    exit();
}

try {
    // Verificar que el usuario es el dueño del comentario
    $stmt = $pdo->prepare("SELECT user_id, media_type, media_id FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
        echo "No tienes permiso para eliminar este comentario.";
        exit();
    }

    // Eliminar el comentario
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);

    // Redirigir de vuelta a la página de comentarios de la foto o video
    if ($comment['media_type'] == 'photo') {
        header('Location: view_comments.php?photo_id=' . $comment['media_id']);
    } else if ($comment['media_type'] == 'video') {
        header('Location: view_video_comments.php?video_id=' . $comment['media_id']);
    }
    exit();

} catch (PDOException $e) {
    echo "Error al eliminar el comentario: " . $e->getMessage();
    exit();
}
?>