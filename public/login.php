<?php
// Incluir el archivo de configuración de la base de datos
require_once '../includes/config.php';

// Incluir el archivo de funciones de autenticación
require_once '../includes/auth.php';

$error_message = '';

// Lógica para manejar el envío del formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    if (empty($username_email) || empty($password)) {
        $error_message = 'Por favor, ingresa tu nombre de usuario/correo electrónico y contraseña.';
    } else {
        // Intentar iniciar sesión
        if (loginUser($username_email, $password, $pdo)) {
            // Inicio de sesión exitoso, redirigir al usuario a la página principal (index.php)
            header('Location: ../index.php');
            exit();
        } else {
            $error_message = 'Nombre de usuario/correo electrónico o contraseña incorrectos.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="input-group">
                <i class="fas fa-smoking"></i>
                <input type="text" id="username_email" name="username_email" placeholder="Usuario o Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-beer"></i>
                <input type="password" id="password" name="password" placeholder="Contraseña" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p class="link-text">¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>
    </div>
</body>
</html>