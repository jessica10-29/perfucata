<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Fragancia</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    function format_cop($value) {
        return '$' . number_format((float)$value, 0, ',', '.');
    }
    ?>
    <header>
        <h1>Detalles de la Fragancia</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="products.php">Fragancias</a>
            <a href="blog.php">Blog</a>
            <a href="contact.php">Contacto</a>
        </nav>
    </header>

    <div class="back-row"><a href="javascript:history.back();" class="btn-outline">← Volver</a></div>

<main>
        <section class="product-details glass-card">
            <?php
            include 'db_connect.php';

            $conn->query("ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS descuento DECIMAL(5,2) DEFAULT 0.00");
            $conn->query("ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS destacado TINYINT(1) DEFAULT 0");
            $conn->query("ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS recomendado TINYINT(1) DEFAULT 0");

            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            $stmt = $conn->prepare("SELECT id, nombre_titulo as name, clave_imagen as image, contenido_texto as description, precio as price, descuento, estado as availability, relacion_id FROM perfumeria_total WHERE id = ? AND tipo = 'producto'");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $categoryNames = [];
            $catRes = $conn->query("SELECT id, nombre_titulo FROM perfumeria_total WHERE tipo='categoria'");
            while ($cat = $catRes->fetch_assoc()) {
                $categoryNames[$cat['id']] = $cat['nombre_titulo'];
            }

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $discount = floatval($row['descuento']);
                $finalPrice = $discount > 0 ? $row['price'] * (1 - ($discount / 100)) : $row['price'];
                $availabilityClass = ($row['availability'] === 'Disponible') ? 'available' : (($row['availability'] === 'Agotado') ? 'out' : 'waiting');
                $categoryLabel = isset($categoryNames[$row['relacion_id']]) ? $categoryNames[$row['relacion_id']] : 'Perfume';

                echo "<div class='details-layout'>";
                echo "<img src='" . $row["image"] . "' alt='" . $row["name"] . "'>";
                echo "<div class='details-body'>";
                echo "<span class='chip muted'>" . $categoryLabel . "</span>";
                echo "<h2>" . $row["name"] . "</h2>";
                echo "<p class='description'>" . $row["description"] . "</p>";
                echo "<p>Disponibilidad: <span class='status " . $availabilityClass . "'>" . $row["availability"] . "</span></p>";
                echo "<div class='price-block'>";
                if ($discount > 0) {
                    echo "<span class='badge badge-offer'>-" . number_format($row['descuento'], 2) . "%</span>";
                    echo "<span class='old-price'>" . format_cop($row["price"]) . "</span>";
                }
                echo "<span class='price'>" . format_cop($finalPrice) . "</span>";
                echo "</div>";
                echo "<div class='card-actions'>";
                echo "<a href='cart.php?id=" . $row["id"] . "' class='btn-gold'>Agregar al carrito</a>";
                echo "<a href='https://wa.me/3135086534?text=" . rawurlencode('Hola! Quiero más información sobre: ' . $row["name"]) . "' target='_blank' class='btn-outline'><i class='fab fa-whatsapp'></i> Consultar por WhatsApp</a>";
                echo "</div>";
                echo "</div></div>";
            } else {
                echo "<p>Producto no encontrado.</p>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Perfume Catalog</p>
    </footer>
</body>
</html>



