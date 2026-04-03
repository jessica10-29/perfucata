<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Administrador - Aura Essence</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Aura Essence</h1>
        <nav>
            <a href="index.php">Regresar al Sitio</a>
        </nav>
    </header>

    <div class="login-container glass-card">
        <h2 style="text-align: center; margin-bottom: 30px;" class="highlight-gold">Acceso Admin</h2>
        <?php if(isset($_GET['error'])) echo "<p style='color: var(--danger); text-align: center;'>Credenciales incorrectas</p>"; ?>
        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-gold" style="width: 100%; border: none; cursor: pointer;">Entrar al Panel</button>
        </form>
    </div>
</body>
</html>