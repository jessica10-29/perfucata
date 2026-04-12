<?php
header('Content-Type: text/html; charset=utf-8');
include 'db_connect.php';

$schemaUpdates = [
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS descuento DECIMAL(5,2) DEFAULT 0.00",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS destacado TINYINT(1) DEFAULT 0",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS recomendado TINYINT(1) DEFAULT 0"
];
foreach ($schemaUpdates as $sql) { $conn->query($sql); }

function format_cop($value) {
    return '$' . number_format((float)$value, 0, ',', '.');
}

// Datos base y filtro de categoría
$categories = [];
$categoryRes = $conn->query("SELECT id, nombre_titulo FROM perfumeria_total WHERE tipo='categoria' ORDER BY nombre_titulo");
while ($c = $categoryRes->fetch_assoc()) { $categories[] = $c; }

$categoryFilter = isset($_GET['category']) ? intval($_GET['category']) : 0;
$select = "id, nombre_titulo as name, precio as price, descuento, destacado, recomendado, clave_imagen as image, estado as availability, contenido_texto as description, relacion_id";
$sql = "SELECT $select FROM perfumeria_total WHERE tipo='producto'";
if ($categoryFilter > 0) {
    $sql .= " AND relacion_id = " . $categoryFilter;
}
$sql .= " ORDER BY destacado DESC, descuento DESC, fecha_registro DESC";
$result = $conn->query($sql);

$currentCategoryName = null;
if ($categoryFilter > 0) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $categoryFilter) { $currentCategoryName = $cat['nombre_titulo']; break; }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Fragancias | Aura Essence</title>
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
            <a href="products.php" class="active">Fragancias</a>
            <a href="blog.php">Blog</a>
            <a href="contact.php">Contacto</a>
        </nav>
    </div>
</header>

<div class="back-row"><a href="javascript:history.back();" class="btn-outline">← Volver</a></div>

<main class="catalog-page">
        <section class="section-head" style="padding:0 0 10px 0;">
        <div>
            <p class="eyebrow"><?php echo $currentCategoryName ? 'Categoría seleccionada' : 'Catálogo completo'; ?></p>
            <h2><?php echo $currentCategoryName ? $currentCategoryName : 'Perfumes listos para ti'; ?></h2>
            <p class="lead" style="color:#cbd5e1; max-width:720px;">
                <?php if ($currentCategoryName): ?>
                    Fragancias de la línea <?php echo $currentCategoryName; ?>, listas para elegir.
                <?php else: ?>
                    Explora nuestras lociones premium. Imágenes optimizadas para ver el frasco completo en cualquier pantalla.
                <?php endif; ?>
            </p>
        </div>
        <div class="hero-summary" style="max-width:360px;">
            <div><strong><?php echo $result->num_rows; ?></strong><small>Perfumes</small></div>
            <div><strong><?php echo $conn->query("SELECT COUNT(*) c FROM perfumeria_total WHERE tipo='producto' AND descuento>0")->fetch_assoc()['c']; ?></strong><small>Con descuento</small></div>
        </div>
    </section>


    <section class="products">
        <div class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()):
                    $discount = floatval($row['descuento']);
                    $finalPrice = $discount > 0 ? $row['price'] * (1 - ($discount / 100)) : $row['price'];
                    $availabilityClass = ($row['availability'] === 'Disponible') ? 'available' : (($row['availability'] === 'Agotado') ? 'out' : 'waiting');
                    $catLabel = '';
                    foreach ($categories as $cat) { if ($cat['id'] == $row['relacion_id']) { $catLabel = $cat['nombre_titulo']; break; } }
                    $desc = trim($row['description'] ?? '');
                    if ($desc === '') { $desc = 'Fragancia ' . strtolower($catLabel ?: 'exclusiva') . '.'; }
                ?>
                <div class="product glass-card">
                    <div class="card-badges">
                        <?php if ($discount > 0): ?><span class="badge badge-offer">-<?php echo number_format($row['descuento'], 2); ?>%</span><?php endif; ?>
                        <?php if ($row['destacado']): ?><span class="badge badge-featured">Destacado</span><?php endif; ?>
                        <?php if ($row['recomendado']): ?><span class="badge badge-reco">Recomendado</span><?php endif; ?>
                        <span class="status <?php echo $availabilityClass; ?>"><?php echo $row['availability']; ?></span>
                    </div>
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <p class="img-caption"><?php echo $desc; ?></p>
                    <div class="product-meta">
                        <span class="chip muted"><?php echo $catLabel ?: 'Perfume'; ?></span>
                        <h3><?php echo $row['name']; ?></h3>
                        <p class="description"><?php echo strlen($desc) > 90 ? substr($desc,0,90).'…' : $desc; ?></p>
                        <div class="price-block">
                            <?php if ($discount > 0): ?><span class="old-price"><?php echo format_cop($row['price']); ?></span><?php endif; ?>
                            <span class="price"><?php echo format_cop($finalPrice); ?></span>
                        </div>
                        <div class="card-actions">
                            <a href="cart.php?id=<?php echo $row['id']; ?>" class="btn-gold">Agregar al carrito</a>
                            <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn-outline">Ver detalle</a>
                        </div>
                        <a href="https://wa.me/3135086534?text=Hola! Estoy interesado en el perfume: <?php echo rawurlencode($row['name']); ?>" target="_blank" class="wa-link"><i class="fab fa-whatsapp"></i> Consultar por WhatsApp</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; width:100%;">No hay productos cargados aún.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Aura Essence</p>
</footer>
</body>
</html>
<?php $conn->close(); ?>


