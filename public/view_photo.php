<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/config.php';

$photo = null;
if (isset($_GET['photo_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT id, title, file_path FROM photos WHERE id = ?");
        $stmt->execute([$_GET['photo_id']]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al cargar la foto: " . $e->getMessage();
        exit();
    }
}

if (!$photo) {
    header('Location: photos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($photo['title'] ?? 'Foto'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .img-container {
            max-width: 90%;
            max-height: 80vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .back-button-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="img-container">
        <img src="<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title'] ?? 'Foto'); ?>">
    </div>
    <div class="back-button-container">
        <a href="photos.php" class="btn btn-primary">Volver a la Galería</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>