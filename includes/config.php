<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Usuario predeterminado de XAMPP
define('DB_PASSWORD', ''); // Contraseña vacía para el usuario 'root' en XAMPP
define('DB_NAME', 'puskas_db'); // Reemplaza con el nombre de tu base de datos

// Intentar conectar a la base de datos MySQL usando PDO
try {
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: No se pudo conectar. " . $e->getMessage());
}

?>