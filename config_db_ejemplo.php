<?php
// Mostrar errores (solo para pruebas, luego puedes quitarlo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DATOS DE INFINITYFREE (ajusta el hostname)
$servername = "sql300.infinityfree.com"; // ⚠️ CAMBIA esto por el real
$username   = "if0_41640364";
$password   = "0UWIMxGfpNg";
$dbname     = "if0_41640364_perfucata";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Validar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Opcional: configurar charset
$conn->set_charset("utf8");
?>