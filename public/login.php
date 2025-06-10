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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post" novalidate>
                <div class="mb-3">
                    <label for="username_email" class="form-label">Usuario o Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username_email" name="username_email" placeholder="Usuario o Email" required />
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required />
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
            </form>
            <p class="text-center mt-3">¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
