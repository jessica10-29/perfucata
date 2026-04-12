<?php
// Activar visualización de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar entorno (localhost vs servidor en línea)
$isLocalhost = ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

if ($isLocalhost) {
    // Configuración para LOCALHOST
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "perfucata";
} else {
    // Configuración para INFINITYFREE (actualiza con tus credenciales reales)
    $servername = "localhost"; // InfinityFree usa localhost
    $username = "if0_41640364_perfucata"; // Reemplaza con tu usuario
    $password = "tu_contraseña_aqui"; // Reemplaza con tu contraseña
    $dbname = "if0_41640364_perfucata"; // Reemplaza con tu nombre de BD
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>