<?php
// Verificación del sistema de secrets
echo "<h1>🔐 Verificación de Secrets</h1>";

// Verificar entorno
$entorno = (isset($_SERVER['HTTP_HOST']) &&
           ($_SERVER['HTTP_HOST'] === 'localhost' ||
            strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
            strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false)) ? 'LOCALHOST' : 'SERVIDOR REMOTO';

echo "<h2>Entorno: $entorno</h2>";

// Verificar secrets
echo "<h2>Variables de Entorno (Secrets):</h2>";
$secrets = ['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME'];
echo "<ul>";
foreach ($secrets as $secret) {
    $valor = getenv($secret);
    $estado = $valor ? '<span style="color: green;">✅ Configurada</span>' : '<span style="color: red;">❌ No configurada</span>';
    echo "<li><strong>$secret:</strong> $estado</li>";
}
echo "</ul>";

// Verificar conexión si estamos en servidor remoto
if ($entorno === 'SERVIDOR REMOTO') {
    echo "<h2>Conexión a Base de Datos:</h2>";
    try {
        $servername = getenv('DB_HOST') ?: 'localhost';
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $dbname = getenv('DB_NAME');

        if ($username && $password && $dbname) {
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                echo "<p style='color: red;'>❌ Error de conexión: " . $conn->connect_error . "</p>";
            } else {
                echo "<p style='color: green;'>✅ Conexión exitosa</p>";
                $conn->close();
            }
        } else {
            echo "<p style='color: red;'>❌ Faltan configurar algunas variables de entorno</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<h2>Conexión Localhost:</h2>";
    echo "<p style='color: blue;'>ℹ️ En localhost usa configuración por defecto (XAMPP)</p>";
}

echo "<p><a href='index.php'>← Ir al sitio</a></p>";
?>