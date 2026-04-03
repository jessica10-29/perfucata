<?php
header('Content-Type: text/html; charset=utf-8');
include 'db_connect.php';

// columnas necesarias
$schemaUpdates = [
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS descuento DECIMAL(5,2) DEFAULT 0.00",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS destacado TINYINT(1) DEFAULT 0",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS recomendado TINYINT(1) DEFAULT 0"
];
foreach ($schemaUpdates as $sql) { $conn->query($sql); }

// asegurar categoría Infantil
$conn->query("INSERT INTO perfumeria_total (tipo, nombre_titulo) SELECT 'categoria','Infantil' WHERE NOT EXISTS (SELECT 1 FROM perfumeria_total WHERE tipo='categoria' AND nombre_titulo='Infantil')");

// filtros
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';

$categories = [];
$categoryRes = $conn->query("SELECT id, nombre_titulo FROM perfumeria_total WHERE tipo='categoria' ORDER BY nombre_titulo");
while ($c = $categoryRes->fetch_assoc()) { $categories[] = $c; }

$allNames = [];
$nameRes = $conn->query("SELECT nombre_titulo FROM perfumeria_total WHERE tipo='producto' ORDER BY nombre_titulo");
while ($n = $nameRes->fetch_assoc()) { $allNames[] = $n['nombre_titulo']; }

$filters = [];$types='';$params=[];
$select = "id, nombre_titulo as name, precio as price, descuento, destacado, recomendado, clave_imagen as image, estado as availability, contenido_texto as description, relacion_id";
$query = "SELECT $select FROM perfumeria_total WHERE tipo='producto'";
if ($q !== '') { $filters[] = "(nombre_titulo LIKE ? OR contenido_texto LIKE ?)"; $types.='ss'; $like="%$q%"; $params[]=$like; $params[]=$like; }
if ($category > 0) { $filters[] = "relacion_id = ?"; $types.='i'; $params[]=$category; }
if ($availability !== '') { $filters[] = "estado = ?"; $types.='s'; $params[]=$availability; }
if ($filters) { $query .= " AND " . implode(' AND ', $filters); }
$query .= " ORDER BY destacado DESC, descuento DESC, fecha_registro DESC LIMIT 48";
$stmt = $conn->prepare($query);
if ($types) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$results = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Perfume | Aura Essence</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header>
    <h1>Buscar perfume</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="products.php">Fragancias</a>
        <a href="blog.php">Blog</a>
        <a href="contact.php">Contacto</a>
    </nav>
</header>

<div class="back-row"><a href="javascript:history.back();" class="btn-outline">← Volver</a></div>

<main class="catalog-page">
    <section class="search-panel glass-card">
        <div class="search-head">
            <div>
                <p class="eyebrow">Encuentra tu aroma</p>
                <h3>Buscador de perfumes</h3>
                <p class="lead" style="color:#cbd5e1;">Busca por nombre, categoría o disponibilidad y te mostramos el perfume exacto.</p>
            </div>
            <div class="search-total"><strong><?php echo $results->num_rows; ?></strong><small>coincidencias</small></div>
        </div>
        <form class="search-grid" method="GET" action="search.php">
            <label>
                <span>Nombre del perfume</span>
                <input list="perfume-names" type="search" name="q" placeholder="Ej. Sauvage, Chanel, Boss" value="<?php echo htmlspecialchars($q); ?>" />
                <datalist id="perfume-names">
                    <?php foreach ($allNames as $n): ?>
                        <option value="<?php echo htmlspecialchars($n); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </label>
            <label>
                <span>Categoría</span>
                <select name="category">
                    <option value="0">Todas</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($category == $cat['id']) ? 'selected' : ''; ?>><?php echo $cat['nombre_titulo']; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Disponibilidad</span>
                <select name="availability">
                    <option value="">Todas</option>
                    <option value="Disponible" <?php echo ($availability === 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                    <option value="Agotado" <?php echo ($availability === 'Agotado') ? 'selected' : ''; ?>>Agotado</option>
                    <option value="En Espera" <?php echo ($availability === 'En Espera') ? 'selected' : ''; ?>>En espera</option>
                </select>
            </label>
            <div class="filter-actions">
                <button type="submit" class="btn-gold">Buscar</button>
                <a class="btn-outline" href="search.php">Limpiar</a>
            </div>
        </form>
    </section>

    <section class="products" id="resultados">
        <div class="section-head">
            <h2>Resultados</h2>
            <p><?php echo $results->num_rows; ?> perfumes encontrados</p>
        </div>
        <div class="product-grid">
            <?php if ($results->num_rows > 0): ?>
                <?php while($row = $results->fetch_assoc()):
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
                            <?php if ($discount > 0): ?><span class="old-price">$<?php echo number_format($row['price'], 2); ?></span><?php endif; ?>
                            <span class="price">$<?php echo number_format($finalPrice, 2); ?></span>
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
                <p style="text-align:center; width:100%;">No hay coincidencias con esos filtros.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Aura Essence</p>
</footer>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>

