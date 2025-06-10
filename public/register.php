<?php
// Incluir el archivo de configuración de la base de datos
require_once '../includes/config.php';

// Incluir el archivo de funciones de autenticación
require_once '../includes/auth.php';

$error_message = '';

// Lógica para manejar el envío del formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'El formato del correo electrónico no es válido.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error_message = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Intentar registrar al usuario
        if (registerUser($username, $email, $password, $pdo)) {
            // Registro exitoso, redirigir al usuario a la página de inicio de sesión
            header('Location: login.php');
            exit();
        } else {
            $error_message = 'Error al registrar el usuario. El nombre de usuario o correo electrónico ya existen.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h2 class="card-title text-center mb-4">Registrarse</h2>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmar Contraseña" required>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </div>
            </form>
            <p class="text-center mt-3">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>