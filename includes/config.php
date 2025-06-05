<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Usuario predeterminado de XAMPP
define('DB_PASSWORD', ''); // Contraseña vacía para el usuario 'root' en XAMPP
define('DB_NAME', 'puskas_db'); // Reemplaza con el nombre de tu base de datos

// Intentar conectar a la base de datos MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Comprobar conexión
if($link === false){
    die("ERROR: No se pudo conectar. " . mysqli_connect_error());
}

?>