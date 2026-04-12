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
    // Configuración para SERVIDOR REMOTO - Cargar desde archivo seguro
    if (file_exists('config_db.php')) {
        include 'config_db.php';
    } else {
        // Valores por defecto si no existe el archivo (para evitar errores)
        $servername = "localhost";
        $username = "usuario_por_defecto";
        $password = "contraseña_por_defecto";
        $dbname = "base_por_defecto";
    }
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>