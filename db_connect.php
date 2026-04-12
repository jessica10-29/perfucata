<?php
// Configuración simplificada para LOCALHOST
// Solo funciona en desarrollo local

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perfucata";

// Activar errores solo en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error: No se puede conectar a la base de datos.<br>
         Asegúrate de que:<br>
         1. XAMPP esté ejecutándose (Apache y MySQL)<br>
         2. La base de datos 'perfucata' existe<br>
         3. Has importado el archivo database.sql<br><br>
         <strong>Error técnico:</strong> " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>