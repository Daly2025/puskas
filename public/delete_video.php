<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$video_id = $_GET['video_id'] ?? null;

if (!$video_id) {
    echo "ID de video no proporcionado.";
    exit();
}

try {
    // Verificar que el usuario es el dueño del video y obtener la ruta del archivo
    $stmt = $pdo->prepare("SELECT user_id, file_path FROM videos WHERE id = ?");
    $stmt->execute([$video_id]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$video || $video['user_id'] != $_SESSION['user_id']) {
        echo "No tienes permiso para eliminar este video.";
        exit();
    }

    // Eliminar el archivo físico del video
    $file_path = __DIR__ . '/' . $video['file_path'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Eliminar la entrada de la base de datos
    $stmt = $pdo->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->execute([$video_id]);

    header('Location: videos.php');
    exit();

} catch (PDOException $e) {
    echo "Error al eliminar el video: " . $e->getMessage();
    exit();
}
?>