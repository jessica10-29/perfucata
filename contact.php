<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <h1>Contáctanos</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="products.php">Fragancias</a>
            <a href="blog.php">Blog</a>
            <a href="contact.php">Contacto</a>
        </nav>
    </header>

    <main>
        <section class="login-container glass-card">
            <h2 style="text-align: center; margin-bottom: 20px;" class="highlight-gold">Ponte en contacto</h2>
            <p style="text-align: center; color: var(--text-dim); margin-bottom: 30px; font-size: 0.9rem;">
                ¿Tienes alguna duda sobre nuestras fragancias? Escríbenos y te asesoraremos personalmente.
            </p>
            
            <form action="contact.php" method="post">
                <div class="form-group">
                    <label for="name" style="display: block; margin-bottom: 8px; font-size: 0.8rem; color: var(--gold);">Nombre:</label>
                    <input type="text" id="name" name="name" placeholder="Tu nombre completo" required>
                </div>

                <div class="form-group">
                    <label for="email" style="display: block; margin-bottom: 8px; font-size: 0.8rem; color: var(--gold);">Correo electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="form-group">
                    <label for="message" style="display: block; margin-bottom: 8px; font-size: 0.8rem; color: var(--gold);">Mensaje:</label>
                    <textarea id="message" name="message" rows="4" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); color: #fff; border-radius: 8px; outline: none;" placeholder="¿En qué podemos ayudarte?" required></textarea>
                </div>

                <button type="submit" class="btn-gold" style="width: 100%; border: none; cursor: pointer; margin-top: 10px;">
                    <i class="fas fa-paper-plane"></i> Enviar Mensaje
                </button>
            </form>
            
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = htmlspecialchars($_POST['name']);
                echo "<p style='color: var(--success); text-align: center; margin-top: 20px; font-weight: bold;'>
                        <i class='fas fa-check-circle'></i> ¡Gracias, $name! Tu mensaje ha sido enviado con éxito.
                      </p>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aura Essence - El arte de la perfumería de lujo.</p>
    </footer>

    <!-- Botón Flotante de WhatsApp -->
    <a href="https://wa.me/5491122334455?text=Hola! Tengo una duda desde la página de contacto." class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
</body>
</html>