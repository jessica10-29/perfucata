<?php
// Detectar si es localhost o servidor remoto
$isLocalhost = (isset($_SERVER['HTTP_HOST']) &&
                ($_SERVER['HTTP_HOST'] === 'localhost' ||
                 strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
                 strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false));

if ($isLocalhost) {
    // LOCALHOST
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "perfucata";

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // SERVIDOR (InfinityFree)
    $configFile = __DIR__ . '/secure/config.php';
    if (!file_exists($configFile)) {
        $configFile = __DIR__ . '/config.php';
    }

    if (!file_exists($configFile)) {
        die("Error: Falta config.php");
    }

    $config = include $configFile;
    if (!is_array($config)) {
        die("Error: Configuración inválida.");
    }

    $servername = $config['DB_HOST'] ?? $config['DB_HOST_LOCAL'] ?? '';
    $username = $config['DB_USER'] ?? $config['DB_USER_LOCAL'] ?? '';
    $password = $config['DB_PASS'] ?? $config['DB_PASS_LOCAL'] ?? '';
    $dbname = $config['DB_NAME'] ?? $config['DB_NAME_LOCAL'] ?? '';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Validar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Charset
$conn->set_charset("utf8mb4");
?>