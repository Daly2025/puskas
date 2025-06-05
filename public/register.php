<?php
// Incluir el archivo de configuración de la base de datos
require_once '../includes/config.php';

// Incluir el archivo de funciones de autenticación (lo crearemos después)
// require_once '../includes/auth.php';

// Lógica para manejar el envío del formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí irá la lógica para procesar el registro
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
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="style.css"> <!-- Asumiendo que tienes un archivo CSS -->
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="register.php" method="POST">
        <div>
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit">Registrarse</button>
    </form>
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
</body>
</html>