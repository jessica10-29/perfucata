<?php
// Diagnóstico rápido para verificar que todo funciona
echo "<h1>🔍 Verificación Rápida</h1>";

// Verificar conexión a BD
echo "<h2>Base de Datos:</h2>";
try {
    include 'db_connect.php';
    echo "<p style='color: green;'>✅ Conexión OK</p>";

    $result = $conn->query("SELECT COUNT(*) as total FROM perfumeria_total");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p>Productos en BD: <strong>" . $row['total'] . "</strong></p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Solución:</strong> Asegúrate de que XAMPP esté ejecutándose</p>";
}

// Verificar archivos
echo "<h2>Archivos Importantes:</h2>";
$archivos = ['index.php', 'style.css', 'products.php'];
foreach ($archivos as $archivo) {
    $existe = file_exists($archivo);
    $estado = $existe ? '✅' : '❌';
    echo "<p>$estado $archivo</p>";
}

echo "<p><a href='index.php'>← Ir al sitio</a></p>";
?>