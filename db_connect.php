<?php
// 🔐 CONFIGURACIÓN USANDO VARIABLES DE ENTORNO (SECRETS)
// Las credenciales están FUERA del código para máxima seguridad
// Se sincronizan automáticamente con InfinityFree vía variables de entorno

// Detectar entorno automáticamente
$isLocalhost = (isset($_SERVER['HTTP_HOST']) &&
                ($_SERVER['HTTP_HOST'] === 'localhost' ||
                 strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
                 strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false));

if ($isLocalhost) {
    // LOCALHOST: Usar valores por defecto para desarrollo
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "perfucata";

    // Mostrar errores en desarrollo
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // SERVIDOR REMOTO: Usar variables de entorno (secrets)
    $servername = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: die('Error: Variable de entorno DB_USER no configurada');
    $password = getenv('DB_PASS') ?: die('Error: Variable de entorno DB_PASS no configurada');
    $dbname = getenv('DB_NAME') ?: die('Error: Variable de entorno DB_NAME no configurada');

    // No mostrar errores en producción por seguridad
    error_reporting(0);
    ini_set('display_errors', 0);
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    if ($isLocalhost) {
        die("Error: No se puede conectar a la base de datos.<br>
             Asegúrate de que XAMPP esté ejecutándose (Apache y MySQL).<br>
             Error técnico: " . $conn->connect_error);
    } else {
        die("Error de conexión a la base de datos. Contacta al administrador.");
    }
}

$conn->set_charset("utf8mb4");
?>
         3. Has importado el archivo database.sql<br><br>
         <strong>Error técnico:</strong> " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>