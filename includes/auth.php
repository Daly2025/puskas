<?php

// Incluir el archivo de configuración de la base de datos
require_once 'config.php';

/**
 * Registra un nuevo usuario en la base de datos.
 * @param string $username El nombre de usuario.
 * @param string $email El correo electrónico del usuario.
 * @param string $password La contraseña del usuario (se hasheará antes de guardar).
 * @param PDO $pdo Objeto PDO para la conexión a la base de datos.
 * @return bool True si el registro fue exitoso, false en caso contrario.
 */
function registerUser($username, $email, $password, $pdo) {
    // Hashear la contraseña antes de guardarla en la base de datos
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash]);
        return true;
    } catch (PDOException $e) {
        // En un entorno de producción, deberías loggear el error en lugar de mostrarlo
        error_log("Error al registrar usuario: " . $e->getMessage());
        return false;
    }
}

/**
 * Inicia la sesión de un usuario.
 * @param string $username_email El nombre de usuario o correo electrónico.
 * @param string $password La contraseña del usuario.
 * @param PDO $pdo Objeto PDO para la conexión a la base de datos.
 * @return bool True si el inicio de sesión fue exitoso, false en caso contrario.
 */
function loginUser($username_email, $password, $pdo) {
    try {
        // Buscar al usuario por nombre de usuario o correo electrónico
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username_email, $username_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Contraseña correcta, iniciar sesión
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        } else {
            // Usuario o contraseña incorrectos
            return false;
        }
    } catch (PDOException $e) {
        error_log("Error al iniciar sesión: " . $e->getMessage());
        return false;
    }
}

/**
 * Cierra la sesión del usuario actual.
 */
function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: login.php'); // Redirigir al usuario a la página de inicio de sesión
    exit();
}

?>