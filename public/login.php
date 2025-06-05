<?php
// Incluir el archivo de configuración de la base de datos
require_once '../includes/config.php';

// Incluir el archivo de funciones de autenticación (lo crearemos después)
// require_once '../includes/auth.php';

// Lógica para manejar el envío del formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí irá la lógica para procesar el inicio de sesión
    // Por ahora, solo mostraremos los datos enviados
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="style.css"> <!-- Asumiendo que tienes un archivo CSS -->
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form action="login.php" method="POST">
        <div>
            <label for="username_email">Nombre de Usuario o Correo Electrónico:</label>
            <input type="text" id="username_email" name="username_email" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>
</body>
</html>