<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, clave_imagen as password FROM perfumeria_total WHERE nombre_titulo = ? AND tipo = 'admin' LIMIT 1");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($pass === $row['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            header("Location: admin_panel.php");
            exit();
        }
    }
    
    header("Location: admin_login.php?error=1");
    exit();
}
?>