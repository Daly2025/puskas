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
    $stmt = $pdo->prepare("SELECT id, title, file_path FROM photos ORDER BY id DESC");
    $stmt->execute();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Galería de Fotos</h1>
        <?php if (empty($photos)): ?>
            <div class="alert alert-info text-center" role="alert">
                Aún no has subido ninguna foto.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($photos as $photo): ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($photo['file_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($photo['title'] ?? 'Foto'); ?>">
                            <div class="card-body">
                                <?php if (!empty($photo['title'])): ?>
                                    <h5 class="card-title"><?php echo htmlspecialchars($photo['title']); ?></h5>
                                <?php endif; ?>
                                <div class="d-grid gap-2">
                                    <a href="comment.php?photo_id=<?php echo $photo['id']; ?>" class="btn btn-primary">Comentar</a>
                                    <a href="view_comments.php?photo_id=<?php echo $photo['id']; ?>" class="btn btn-info">Ver comentarios</a>
                                    <a href="edit_photo.php?photo_id=<?php echo $photo['id']; ?>" class="btn btn-warning">Editar</a>
                                    <a href="delete_photo.php?photo_id=<?php echo $photo['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta foto?');">Eliminar</a>
                                     <a href="<?php echo htmlspecialchars($photo['file_path']); ?>" class="btn btn-success" download><i class="bi bi-download"></i></a>
                                 </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
