<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <h1 class="highlight-gold">Aura Blog</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="products.php">Fragancias</a>
            <a href="blog.php" style="color: var(--gold)">Blog</a>
            <a href="contact.php">Contacto</a>
        </nav>
    </header>

    <main style="padding: 50px 10%;">
        <section class="blog-container">
            <h2 style="text-align: center; font-size: 2.5rem; color: #000; margin-bottom: 40px;">Secretos de Perfumería</h2>
            <?php
            include 'db_connect.php';

            $sql = "SELECT nombre_titulo as title, clave_imagen as image, contenido_texto as content, fecha_registro as date_posted FROM perfumeria_total WHERE tipo = 'blog' ORDER BY fecha_registro DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='blog-post glass-card' style='margin-bottom: 30px; padding: 30px;'>";
                    if(!empty($row['image'])) echo "<img src='" . $row["image"] . "' style='width:100%; height:300px; object-fit:cover; border-radius:15px; margin-bottom:20px;'>";
                    echo "<h3 class='highlight-gold' style='font-size: 1.8rem; margin-bottom: 15px;'>" . $row["title"] . "</h3>";
                    echo "<p style='color: #eee; font-size: 1.1rem; margin-bottom: 20px;'>" . nl2br($row["content"]) . "</p>";
                    echo "<div style='border-top: 1px solid var(--glass-border); padding-top: 15px; color: var(--gold); font-size: 0.8rem;'>";
                    echo "<i class='far fa-calendar-alt'></i> Publicado el: " . date('d/m/Y', strtotime($row["date_posted"]));
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align:center; color:#000;'>Próximamente compartiremos más secretos contigo...</p>";
            }
            $conn->close();
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aura Essence - El arte de la perfumería de lujo.</p>
    </footer>

    <!-- Botón Flotante de WhatsApp -->
    <a href="https://wa.me/5491122334455?text=Hola! Leí su blog y tengo una consulta." class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
</body>
</html>