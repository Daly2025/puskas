<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$comment_id = $_GET['comment_id'] ?? null;
$comment_text = '';
$media_type = '';
$media_id = '';

if (!$comment_id) {
    echo "ID de comentario no proporcionado.";
    exit();
}

try {
    // Obtener el comentario para editar
    $stmt = $pdo->prepare("SELECT comment_text, user_id, photo_id FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
        echo "No tienes permiso para editar este comentario.";
        exit();
    }

    $comment_text = $comment['comment_text'];
    $photo_id = $comment['photo_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_comment_text = $_POST['comment_text'] ?? '';

        if (empty($new_comment_text)) {
            echo "El comentario no puede estar vacío.";
        } else {
            $stmt = $pdo->prepare("UPDATE comments SET comment_text = ? WHERE id = ?");
            $stmt->execute([$new_comment_text, $comment_id]);

            // Redirigir de vuelta a la página de comentarios de la foto
            header('Location: view_comments.php?photo_id=' . $photo_id);
            exit();
        }
    }

} catch (PDOException $e) {
    echo "Error al cargar o actualizar el comentario: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comentario</title>
    <link rel="stylesheet" href="css/photos.css">
</head>
<body>
    <div class="container">
        <h1>Editar Comentario</h1>
        <form action="edit_comment.php?comment_id=<?php echo htmlspecialchars($comment_id); ?>" method="post">
            <textarea name="comment_text" rows="5" required><?php echo htmlspecialchars($comment_text); ?></textarea><br>
            <button type="submit">Guardar Cambios</button>
            <a href="view_comments.php?photo_id=<?php echo htmlspecialchars($photo_id); ?>">Cancelar</a>
        </form>
    </div>
</body>
</html>