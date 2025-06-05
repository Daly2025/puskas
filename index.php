<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puskas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .header-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .header-right a {
            margin-left: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .header-right a:hover {
            text-decoration: underline;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header-right">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="public/logout.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="public/login.php">Login</a>
            <a href="public/register.php">Register</a>
        <?php endif; ?>
    </div>

    <h1>Bienvenida a Cas'Pilar</h1>
    <p>Aquí podrás subir y ver fotos y videos.</p>
    <ul>
        <li><a href="index.php">Inicio</a></li>
        <li><a href="public/photos.php">Galería de Fotos</a></li>
        <li><a href="public/videos.php">Galería de Videos</a></li>
    </ul>
</body>
</html>