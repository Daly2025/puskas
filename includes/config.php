<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'admin'); // Reemplaza con tu usuario de MySQL
define('DB_PASSWORD', ''); // Reemplaza con tu contraseña de MySQL
define('DB_NAME', 'puskas_db'); // Reemplaza con el nombre de tu base de datos

// Intentar conectar a la base de datos MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Comprobar conexión
if($link === false){
    die("ERROR: No se pudo conectar. " . mysqli_connect_error());
}

?>