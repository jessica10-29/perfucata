<?php
// Configuración automática para localhost y servidores remotos
// Para InfinityFree: configura config_db.php con tus credenciales reales

// Detectar entorno automáticamente
$isLocalhost = (isset($_SERVER['HTTP_HOST']) &&
                ($_SERVER['HTTP_HOST'] === 'localhost' ||
                 strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
                 strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false));

if ($isLocalhost) {
    // Configuración para LOCALHOST (desarrollo)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "perfucata";

    // Mostrar errores en desarrollo
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // Configuración para SERVIDOR REMOTO (InfinityFree)
    // Cargar desde archivo seguro
    $configFile = __DIR__ . '/config_db.php';
    if (file_exists($configFile)) {
        include $configFile;
    } else {
        die("Error: Archivo de configuración 'config_db.php' no encontrado.<br>
             Crea este archivo en tu servidor InfinityFree con tus credenciales.");
    }

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