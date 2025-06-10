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
try {
    if ($media_type === 'photo') {
        $stmt = $pdo->prepare("SELECT id, file_path, title FROM photos WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("SELECT id, file_path, title FROM videos WHERE id = ?");
    }
    $stmt->execute([$media_id]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al cargar el medio: " . $e->getMessage();
    exit();
}

if (!$media) {
    echo "Medio no encontrado.";
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_text = trim($_POST['comment_text'] ?? '');

    if (!empty($comment_text)) {
        try {
            if ($media_type === 'photo') {
                $stmt = $pdo->prepare("INSERT INTO comments (photo_id, user_id, comment_text) VALUES (?, ?, ?)");
                $stmt->execute([$media_id, $_SESSION['user_id'], $comment_text]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO video_comments (video_id, user_id, comment) VALUES (?, ?, ?)");
                $stmt->execute([$media_id, $_SESSION['user_id'], $comment_text]);
            }
            $message = "Comentario añadido con éxito.";
        } catch (PDOException $e) {
            $message = "Error al añadir el comentario: " . $e->getMessage();
        }
    } else {
        $message = "El comentario no puede estar vacío.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentar Foto</title>
    <link rel="stylesheet" href="css/photos.css">
</head>
<body>
    <div class="container">
        <h1>Comentar <?php echo ($media_type === 'photo' ? 'Foto' : 'Video'); ?></h1>
        <?php if ($media_type === 'photo'): ?>
            <img src="<?php echo htmlspecialchars($media['file_path']); ?>" alt="<?php echo htmlspecialchars($media['title'] ?? 'Foto'); ?>" style="max-width: 300px; height: auto;">
        <?php else: ?>
            <video controls style="max-width: 300px; height: auto;">
                <source src="<?php echo htmlspecialchars($media['file_path']); ?>" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
        <?php endif; ?>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="comment.php?<?php echo $media_type; ?>_id=<?php echo $media_id; ?>" method="POST">
            <textarea name="comment_text" rows="5" placeholder="Escribe tu comentario aquí..." required></textarea><br>
            <button type="submit">Añadir Comentario</button>
        </form>
        <p><a href="../index.php" class="btn btn-primary mt-3">Volver al Inicio</a></p>
    </div>
</body>
</html>