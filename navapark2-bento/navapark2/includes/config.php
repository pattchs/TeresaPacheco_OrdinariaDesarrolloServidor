<?php
// includes/config.php - Configuración de la base de datos

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Cambia por tu usuario de MySQL
define('DB_PASS', '');           // Cambia por tu contraseña de MySQL
define('DB_NAME', 'navapark2');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
