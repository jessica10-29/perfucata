<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <span class="header-spark spark-one" aria-hidden="true"></span>
        <span class="header-spark spark-two" aria-hidden="true"></span>
        <span class="header-spark spark-three" aria-hidden="true"></span>
        <span class="header-scent scent-one" aria-hidden="true"></span>
        <span class="header-scent scent-two" aria-hidden="true"></span>
        <div class="header-inner">
            <h1>Aura Essence</h1>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="products.php">Fragancias</a>
                <a href="blog.php">Blog</a>
                <a href="contact.php">Contacto</a>
            </nav>
        </div>
    </header>

    <div class="back-row"><a href="javascript:history.back();" class="btn-outline">← Volver</a></div>

    <main>
        <section class="cart">
            <h2>Your Cart</h2>
            <?php
            include 'db_connect.php';
            $conn->query("ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS descuento DECIMAL(5,2) DEFAULT 0.00");

            function format_cop($value) {
                return '$' . number_format((float)$value, 0, ',', '.');
            }

            // Reset completo del carrito
            if (isset($_GET['reset'])) {
                unset($_SESSION['shopping_cart']);
            }

            // Eliminar un producto del carrito
            if (isset($_GET['remove'])) {
                $rid = intval($_GET['remove']);
                if (isset($_SESSION['shopping_cart'][$rid])) {
                    unset($_SESSION['shopping_cart'][$rid]);
                }
            }

            // Agregar producto (si new=1, reinicia antes de agregar)
            if (isset($_GET['id'])) {
                $product_id = intval($_GET['id']);
                if (isset($_GET['new']) && $_GET['new'] == '1') {
                    $_SESSION['shopping_cart'] = array();
                }
                if (!isset($_SESSION['shopping_cart'])) { $_SESSION['shopping_cart'] = array(); }

                $stmt = $conn->prepare("SELECT id, nombre_titulo as name, precio as price, descuento, clave_imagen as image FROM perfumeria_total WHERE id = ? AND tipo = 'producto'");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    $finalPrice = ($product['descuento'] > 0) ? $product['price'] * (1 - ($product['descuento'] / 100)) : $product['price'];
                    $_SESSION['shopping_cart'][$product_id] = array(
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $finalPrice,
                        'image' => $product['image'],
                        'quantity' => 1,
                        'discount' => $product['descuento']
                    );
                }
                $stmt->close();
            }

            $total = 0;
            $total_items = 0;
            $wa_message = "*Hola Aura Essence!* Quisiera realizar el siguiente pedido:\n\n";

            if (!empty($_SESSION['shopping_cart'])) {
                echo "<table>";
                echo "<tr><th>Imagen</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th><th></th></tr>";
                foreach($_SESSION['shopping_cart'] as $product) {
                    $subtotal = $product['price'] * $product['quantity'];
                    $total += $subtotal;
                    $total_items += $product['quantity'];

                    $wa_message .= "⭐ Producto: " . $product['name'] . "\n";
                    if (!empty($product['discount'])) { $wa_message .= "🎯 Descuento: -" . $product['discount'] . "%\n"; }
                    $wa_message .= $product['image'] . "\n";
                    $wa_message .= "💵 Precio: " . format_cop($product['price']) . "\n";
                    $wa_message .= "--------------------------\n";

                    echo "<tr>";
                    echo "<td><img src='" . $product['image'] . "' style='width:50px; height:50px; object-fit:cover; border-radius:5px;'></td>";
                    echo "<td>" . $product['name'] . "</td>";
                    echo "<td>" . $product['quantity'] . "</td>";
                    echo "<td>";
                    if (!empty($product['discount'])) {
                        $original = $product['price'] / (1 - ($product['discount'] / 100));
                        echo "<span class='old-price'>" . format_cop($original) . "</span> ";
                    }
                    echo format_cop($product['price']);
                    if (!empty($product['discount'])) {
                        echo " <span class='badge badge-offer'>-" . number_format($product['discount'], 2) . "%</span>";
                    }
                    echo "</td>";
                    echo "<td style='color:var(--gold); font-weight:bold;'>" . format_cop($subtotal) . "</td>";
                    echo "<td><a href='?remove=" . $product['id'] . "' class='btn-outline'>Eliminar</a></td>";
                    echo "</tr>";
                }

                $wa_message .= "\n✅ Total a pagar: " . format_cop($total);
                $wa_url = "https://wa.me/3135086534?text=" . rawurlencode($wa_message);

                echo "<tr style='background:rgba(255,255,255,0.05);'><td colspan='3' align='right'><strong>Total de productos: $total_items</strong></td><td align='right'><strong>Total a Pagar:</strong></td><td style='font-size:1.5rem; color:var(--gold);'><strong>" . format_cop($total) . "</strong></td><td></td></tr>";
                echo "</table>";
                echo "<div style='text-align:right; display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap;'>";
                echo "<a href='?reset=1' class='btn-outline'>Nueva compra</a>";
                echo "<a href='" . $wa_url . "' class='btn-gold' target='_blank'><i class='fab fa-whatsapp'></i> Finalizar Compra por WhatsApp</a>";
                echo "</div>";
            } else {
                echo "<p style='text-align:center;'>Tu carrito está vacío.</p>";
            }
            $conn->close();
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Perfume Catalog</p>
    </footer>
</body>
</html>
