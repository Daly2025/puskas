<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$video_id = $_GET['video_id'] ?? null;
$title = '';
$description = '';
$file_path = '';

if (!$video_id) {
    echo "ID de video no proporcionado.";
    exit();
}

try {
    // Obtener el video para editar
    $stmt = $pdo->prepare("SELECT title, description, user_id, file_path FROM videos WHERE id = ?");
    $stmt->execute([$video_id]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$video || $video['user_id'] != $_SESSION['user_id']) {
        echo "No tienes permiso para editar este video.";
        exit();
    }

    $title = $video['title'] ?? '';
    $description = $video['description'] ?? '';
    $file_path = $video['file_path'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_title = $_POST['title'] ?? '';
        $new_description = $_POST['description'] ?? '';

        $stmt = $pdo->prepare("UPDATE videos SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([$new_title, $new_description, $video_id]);

        header('Location: videos.php');
        exit();
    }

} catch (PDOException $e) {
    echo "Error al cargar o actualizar el video: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Video</title>
    <link rel="stylesheet" href="css/videos.css">
</head>
<body>
    <div class="container">
        <h1>Editar Video</h1>
        <video controls style="max-width: 300px; height: auto;">
            <source src="<?php echo htmlspecialchars($file_path); ?>" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video>
        <form action="edit_video.php?video_id=<?php echo htmlspecialchars($video_id); ?>" method="post">
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
            
            <label for="description">Descripción:</label><br>
            <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($description); ?></textarea><br><br>
            
            <button type="submit">Guardar Cambios</button>
            <a href="videos.php">Cancelar</a>
        </form>
    </div>
</body>
</html>