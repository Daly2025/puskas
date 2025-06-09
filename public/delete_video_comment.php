<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$comment_id = $_GET['comment_id'] ?? null;

if (!$comment_id) {
    echo "ID de comentario de video no proporcionado.";
    exit();
}

try {
    // Verificar que el usuario es el dueño del comentario de video
    $stmt = $pdo->prepare("SELECT user_id, video_id FROM video_comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
        echo "No tienes permiso para eliminar este comentario de video.";
        exit();
    }

    // Eliminar el comentario de video
    $stmt = $pdo->prepare("DELETE FROM video_comments WHERE id = ?");
    $stmt->execute([$comment_id]);

    // Redirigir de vuelta a la página de comentarios del video
    header('Location: view_video_comments.php?video_id=' . $comment['video_id']);
    exit();

} catch (PDOException $e) {
    echo "Error al eliminar el comentario de video: " . $e->getMessage();
    exit();
}
?>