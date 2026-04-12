<?php
// Detectar si es localhost o servidor remoto
$isLocalhost = (isset($_SERVER['HTTP_HOST']) &&
                ($_SERVER['HTTP_HOST'] === 'localhost' ||
                 strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
                 strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false));

if ($isLocalhost) {
    // LOCALHOST - Valores de desarrollo
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "perfucata";
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // SERVIDOR REMOTO - Cargar credenciales desde config.php
    if (file_exists(__DIR__ . '/config.php')) {
        include __DIR__ . '/config.php';
    } else {
        die("Error de configuración: Falta el archivo config.php");
    }
}

// Conectar
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
         3. Has importado el archivo database.sql<br><br>
         <strong>Error técnico:</strong> " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>