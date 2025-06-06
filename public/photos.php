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
    <link rel="stylesheet" href="main.css" />
    <style>
      /* Estilos básicos para la galería */
      .photo-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
      }
      .photo-item {
        max-width: 200px;
        text-align: center;
      }
      .photo-item img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      }
    </style>
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p class="link-text"><a href="../index.php">Volver al Inicio</a></p>
    </div>
</body>
</html>
