<?php
// Activar visualización de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar entorno (localhost vs servidor en línea)
// InfinityFree y otros hostings gratuitos usan dominios como .epizy.com, .000webhost.com, etc.
$isLocalhost = (
    $_SERVER['HTTP_HOST'] === 'localhost' ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
    strpos($_SERVER['HTTP_HOST'], '.local') !== false ||
    strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false
);

if ($isLocalhost) {
    // Configuración para LOCALHOST (desarrollo)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "perfucata";
} else {
    // Configuración para SERVIDOR REMOTO (InfinityFree u otros)
    // Intentar cargar desde archivo seguro
    $configFile = __DIR__ . '/config_db.php';
    if (file_exists($configFile)) {
        include $configFile;
    } else {
        // Si no existe config_db.php, intentar usar variables de entorno
        $servername = getenv('DB_HOST') ?: "localhost";
        $username = getenv('DB_USER') ?: "usuario_por_defecto";
        $password = getenv('DB_PASS') ?: "contraseña_por_defecto";
        $dbname = getenv('DB_NAME') ?: "base_por_defecto";
    }
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error . " - Verifica las credenciales en config_db.php");
}

$conn->set_charset("utf8mb4");
?>