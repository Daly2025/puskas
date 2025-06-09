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
try {
    $stmt = $pdo->prepare("SELECT id, file_path, title FROM photos WHERE id = ? AND user_id = ?");
    $stmt->execute([$photo_id, $_SESSION['user_id']]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al cargar la foto: " . $e->getMessage();
    exit();
}

if (!$photo) {
    echo "Foto no encontrada o no tienes permiso para verla.";
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_text = trim($_POST['comment_text'] ?? '');

    if (!empty($comment_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (media_type, media_id, user_id, comment_text) VALUES (?, ?, ?, ?)");
            $stmt->execute(['photo', $photo_id, $_SESSION['user_id'], $comment_text]);
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
        <h1>Comentar Foto</h1>
        <img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title'] ?? 'Foto'); ?>" style="max-width: 300px; height: auto;">
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="comment.php?photo_id=<?php echo $photo_id; ?>" method="POST">
            <textarea name="comment_text" rows="5" placeholder="Escribe tu comentario aquí..." required></textarea><br>
            <button type="submit">Añadir Comentario</button>
        </form>
        <p><a href="photos.php">Volver a la Galería</a></p>
    </div>
</body>
</html>