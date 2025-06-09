<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$photo_id = $_GET['photo_id'] ?? null;

if (!$photo_id) {
    echo "ID de foto no proporcionado.";
    exit();
}

try {
    // Verificar que el usuario es el dueño de la foto y obtener la ruta del archivo
    $stmt = $pdo->prepare("SELECT user_id, file_path FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$photo || $photo['user_id'] != $_SESSION['user_id']) {
        echo "No tienes permiso para eliminar esta foto.";
        exit();
    }

    // Eliminar el archivo físico de la foto
    $file_path = __DIR__ . '/' . $photo['file_path'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Eliminar la entrada de la base de datos
    $stmt = $pdo->prepare("DELETE FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);

    header('Location: photos.php');
    exit();

} catch (PDOException $e) {
    echo "Error al eliminar la foto: " . $e->getMessage();
    exit();
}
?>