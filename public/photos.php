<?php
session_start();

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php'; // Incluir la configuración de conexión a la base de datos

$photos = [];
try {
    // Consulta para obtener fotos del usuario
    $stmt = $pdo->prepare("SELECT id, title, file_path FROM photos WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al cargar las fotos: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Galería de Fotos</title>
    <link rel="stylesheet" href="public/css/photos.css" />
    <!-- Elimina el bloque de estilos <style>...</style> que estaba aquí -->
</head>
<body>
    <div class="container">
        <h1>Galería de Fotos</h1>
        <?php if (empty($photos)): ?>
            <p>Aún no has subido ninguna foto.</p>
        <?php else: ?>
            <div class="photo-gallery">
                <?php foreach ($photos as $photo): ?>
                    <div class="photo-item">
                        <img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title'] ?? 'Foto'); ?>" />
                        <?php if (!empty($photo['title'])): ?>
                            <p><?php echo htmlspecialchars($photo['title']); ?></p>
                        <?php endif; ?>
                        <div class="buttons">
                            <button onclick="location.href='comment.php?photo_id=<?php echo $photo['id']; ?>'">Comentar</button>
                            <button onclick="location.href='view_comments.php?photo_id=<?php echo $photo['id']; ?>'">Ver comentarios</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p class="link-text"><a href="../index.php">Volver al Inicio</a></p>
    </div>
</body>
</html>
