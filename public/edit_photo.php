<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$photo_id = $_GET['photo_id'] ?? null;
$title = '';
$description = '';
$file_path = '';

if (!$photo_id) {
    echo "ID de foto no proporcionado.";
    exit();
}

try {
    // Obtener la foto para editar
    $stmt = $pdo->prepare("SELECT title, description, user_id, file_path FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$photo || $photo['user_id'] != $_SESSION['user_id']) {
        echo "No tienes permiso para editar esta foto.";
        exit();
    }

    $title = $photo['title'] ?? '';
    $description = $photo['description'] ?? '';
    $file_path = $photo['file_path'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_title = $_POST['title'] ?? '';
        $new_description = $_POST['description'] ?? '';

        $stmt = $pdo->prepare("UPDATE photos SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([$new_title, $new_description, $photo_id]);

        header('Location: photos.php');
        exit();
    }

} catch (PDOException $e) {
    echo "Error al cargar o actualizar la foto: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Foto</title>
    <link rel="stylesheet" href="css/photos.css">
</head>
<body>
    <div class="container">
        <h1>Editar Foto</h1>
        <img src="<?php echo htmlspecialchars($file_path); ?>" alt="<?php echo htmlspecialchars($title); ?>" style="max-width: 300px; height: auto;">
        <form action="edit_photo.php?photo_id=<?php echo htmlspecialchars($photo_id); ?>" method="post">
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
            
            <label for="description">Descripción:</label><br>
            <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($description); ?></textarea><br><br>
            
            <button type="submit">Guardar Cambios</button>
            <a href="photos.php">Cancelar</a>
        </form>
    </div>
</body>
</html>