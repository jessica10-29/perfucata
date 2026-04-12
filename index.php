<?php
// ============================================
// CONFIGURACIÓN E INICIALIZACIÓN
// ============================================
header('Content-Type: text/html; charset=utf-8');
include 'db_connect.php';

// DEBUG: Si hay problemas, ve a check.php para diagnosticar
// http://localhost/perfucata/check.php

// Asegurar columnas para descuentos y flags
$schemaUpdates = [
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS descuento DECIMAL(5,2) DEFAULT 0.00",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS destacado TINYINT(1) DEFAULT 0",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS recomendado TINYINT(1) DEFAULT 0"
];
foreach ($schemaUpdates as $sql) { $conn->query($sql); }

// ============================================
// FUNCIONES AUXILIARES
// ============================================
function format_cop($value) {
    return '$' . number_format((float)$value, 0, ',', '.');
}

// ============================================
// PARÁMETROS DE BÚSQUEDA Y FILTROS
// ============================================
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';
$doSearch = ($q !== '' || $category > 0 || $availability !== '');

// ============================================
// CARGAR DATOS: CATEGORÍAS Y ESTADÍSTICAS
// ============================================
// Nombres para autocompletar
$nameRes = $conn->query("SELECT nombre_titulo FROM perfumeria_total WHERE tipo='producto' ORDER BY nombre_titulo");
$allNames = [];
while ($n = $nameRes->fetch_assoc()) { $allNames[] = $n['nombre_titulo']; }

// Categorías
$categories = [];
$categoryRes = $conn->query("SELECT id, nombre_titulo FROM perfumeria_total WHERE tipo='categoria' ORDER BY nombre_titulo");
while ($c = $categoryRes->fetch_assoc()) { $categories[] = $c; }

// Estadísticas de categorías
$catStats = [];
$statRes = $conn->query("SELECT relacion_id, COUNT(*) total, SUM(CASE WHEN descuento>0 THEN 1 ELSE 0 END) ofertas FROM perfumeria_total WHERE tipo='producto' GROUP BY relacion_id");
while ($s = $statRes->fetch_assoc()) { $catStats[$s['relacion_id']] = $s; }

// ============================================
// BÚSQUEDA DE PRODUCTOS
// ============================================
$results = null;
if ($doSearch) {
    $filters = [];
    $types = '';
    $params = [];
    $select = "id, nombre_titulo as name, precio as price, descuento, destacado, recomendado, clave_imagen as image, estado as availability, contenido_texto as description, relacion_id";
    $query = "SELECT $select FROM perfumeria_total WHERE tipo='producto'";
    
    if ($q !== '') { 
        $filters[] = "(nombre_titulo LIKE ? OR contenido_texto LIKE ?)"; 
        $types .= 'ss'; 
        $like = "%$q%"; 
        $params[] = $like; 
        $params[] = $like; 
    }
    if ($category > 0) { 
        $filters[] = "relacion_id = ?"; 
        $types .= 'i'; 
        $params[] = $category; 
    }
    if ($availability !== '') { 
        $filters[] = "estado = ?"; 
        $types .= 's'; 
        $params[] = $availability; 
    }
    
    if ($filters) { $query .= " AND " . implode(' AND ', $filters); }
    $query .= " ORDER BY destacado DESC, descuento DESC, fecha_registro DESC LIMIT 24";
    
    $stmt = $conn->prepare($query);
    if ($types) { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $results = $stmt->get_result();
}

// ============================================
// CARRUSEL: DATOS DE PRODUCTOS DESTACADOS
// ============================================
$carouselSeeds = [
    [
        'image' => 'images/perfumes/femeninos/chanel-no5.jpg',
        'alt' => 'Perfume femenino Chanel No. 5',
        'fallback_name' => 'Chanel No. 5 Eau de Parfum 100ml',
        'fallback_price' => 829990.00
    ],
    [
        'image' => 'images/perfumes/femeninos/dior-jadore.jpg',
        'alt' => 'Perfume femenino Dior Jadore',
        'fallback_name' => 'Dior Jadore Eau de Parfum 100ml',
        'fallback_price' => 739900.00
    ],
    [
        'image' => 'images/perfumes/masculinos/dior-sauvage.jpg',
        'alt' => 'Perfume masculino Dior Sauvage',
        'fallback_name' => 'Dior Sauvage Eau de Toilette 100ml',
        'fallback_price' => 699000.00
    ],
    [
        'image' => 'images/perfumes/masculinos/acqua-di-gio.jpg',
        'alt' => 'Perfume masculino Acqua di Gio',
        'fallback_name' => 'Acqua di Gio Pour Homme 100ml',
        'fallback_price' => 429990.00
    ],
    [
        'image' => 'images/perfumes/infantil/mustela-musti.png',
        'alt' => 'Colonia infantil Mustela Musti',
        'fallback_name' => 'Mustela Musti Eau de Soin 50ml',
        'fallback_price' => 84900.00
    ]
];

// ============================================
// CARRUSEL: OBTENER DATOS DE LA BASE DE DATOS
// ============================================
$carouselMap = [];
$carouselPaths = array_column($carouselSeeds, 'image');
if (!empty($carouselPaths)) {
    $placeholders = implode(',', array_fill(0, count($carouselPaths), '?'));
    $carouselQuery = "SELECT id, nombre_titulo, precio, descuento, clave_imagen FROM perfumeria_total WHERE tipo='producto' AND clave_imagen IN ($placeholders)";
    $carouselStmt = $conn->prepare($carouselQuery);
    $carouselTypes = str_repeat('s', count($carouselPaths));
    $carouselStmt->bind_param($carouselTypes, ...$carouselPaths);
    $carouselStmt->execute();
    $carouselRes = $carouselStmt->get_result();
    while ($carouselRow = $carouselRes->fetch_assoc()) {
        $carouselMap[$carouselRow['clave_imagen']] = $carouselRow;
    }
}

// ============================================
// CARRUSEL: CONSTRUIR ITEMS CON PRECIOS
// ============================================
$carouselItems = [];
foreach ($carouselSeeds as $seed) {
    $dbItem = $carouselMap[$seed['image']] ?? null;
    $basePrice = $dbItem ? floatval($dbItem['precio']) : floatval($seed['fallback_price']);
    $discount = $dbItem ? floatval($dbItem['descuento']) : 0;
    $finalPrice = $discount > 0 ? $basePrice * (1 - ($discount / 100)) : $basePrice;
    $carouselItems[] = [
        'id' => $dbItem['id'] ?? 0,
        'image' => $seed['image'],
        'alt' => $seed['alt'],
        'name' => $dbItem['nombre_titulo'] ?? $seed['fallback_name'],
        'price' => $finalPrice,
        'base_price' => $basePrice,
        'discount' => $discount
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Essence | Catálogo de Perfumes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="site-header home-header">
    <span class="header-spark spark-one" aria-hidden="true"></span>
    <span class="header-spark spark-two" aria-hidden="true"></span>
    <span class="header-spark spark-three" aria-hidden="true"></span>
    <span class="header-scent scent-one" aria-hidden="true"></span>
    <span class="header-scent scent-two" aria-hidden="true"></span>
    <div class="header-inner">
        <h1>Aura Essence</h1>
        <nav>
            <a href="index.php" class="active">Inicio</a>
            <a href="products.php">Fragancias</a>
            <a href="blog.php">Blog</a>
            <a href="contact.php">Contacto</a>
        </nav>
    </div>
</header>

<main class="home-shell">
    <section class="intro-copy">
        <p class="eyebrow">Alta perfumería · Atención personalizada</p>
        <h2 class="highlight-gold">Elegancia en cada gota</h2>
        <p class="lead">En <strong>Aura Essence</strong> transformamos recuerdos en fragancias. Curamos aromas de autor y firmas globales para que tu presencia hable antes que las palabras.</p>
    </section>

    <section class="intro-showcase" id="buscador">
        <div class="panel-grid top-tools">
            <aside class="category-panel glass-card">
                <h3>Categorías</h3>
                <ul class="category-list">
                    <?php foreach ($categories as $cat): $stats = isset($catStats[$cat['id']]) ? $catStats[$cat['id']] : ['total'=>0,'ofertas'=>0]; ?>
                        <li>
                            <span><?php echo $cat['nombre_titulo']; ?></span>
                            <div class="category-tags">
                                <span class="chip muted">Disponibles: <?php echo $stats['total']; ?></span>
                                <span class="chip">Ofertas: <?php echo $stats['ofertas']; ?></span>
                            </div>
                            <a class="btn-outline" href="products.php?category=<?php echo $cat['id']; ?>">Ver lociones</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <section class="search-panel glass-card" id="buscador-perfume">
                <div class="search-head">
                    <div>
                        <p class="eyebrow">Encuentra tu aroma</p>
                        <h3>Buscar lociones</h3>
                        <p class="lead">Busca por nombre, categoría o disponibilidad para encontrar rápido el perfume ideal.</p>
                    </div>
                    <div class="search-total"><strong><?php echo $doSearch && $results ? $results->num_rows : count($allNames); ?></strong><small><?php echo $doSearch ? 'coincidencias' : 'en catálogo'; ?></small></div>
                </div>
                <form class="search-grid" method="GET" action="index.php">
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
                        <a class="btn-outline" href="index.php">Limpiar</a>
                    </div>
                </form>
            </section>
        </div>

        <section class="carousel top-carousel">
            <div class="carousel-container">
                <?php foreach ($carouselItems as $item): ?>
                    <div class="carousel-slide" style="--slide-image:url('<?php echo htmlspecialchars($item['image']); ?>')">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['alt']); ?>">
                        <span class="carousel-price-tag"><?php echo format_cop($item['price']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-dots pro-dots">
                <button class="dot active" aria-label="Ir a la diapositiva 1"></button>
                <button class="dot" aria-label="Ir a la diapositiva 2"></button>
                <button class="dot" aria-label="Ir a la diapositiva 3"></button>
                <button class="dot" aria-label="Ir a la diapositiva 4"></button>
                <button class="dot" aria-label="Ir a la diapositiva 5"></button>
                <div class="progress-track"><span class="progress-fill"></span></div>
            </div>
        </section>
    </section>

    <?php if ($doSearch && $results && $results->num_rows > 0): ?>
        <section class="products" id="resultados">
            <div class="section-head">
                <h2>Coincidencias</h2>
                <p><?php echo $results->num_rows; ?> perfumes encontrados</p>
            </div>
            <div class="product-grid">
                <?php while($row = $results->fetch_assoc()):
                    $discount = floatval($row['descuento']);
                    $finalPrice = $discount > 0 ? $row['price'] * (1 - ($discount / 100)) : $row['price'];
                    $availabilityClass = ($row['availability'] === 'Disponible') ? 'available' : (($row['availability'] === 'Agotado') ? 'out' : 'waiting');
                    $catLabel = '';
                    foreach ($categories as $cat) { if ($cat['id'] == $row['relacion_id']) { $catLabel = $cat['nombre_titulo']; break; } }
                ?>
                <div class="product glass-card">
                    <div class="card-badges">
                        <?php if ($discount > 0): ?><span class="badge badge-offer">-<?php echo number_format($row['descuento'], 2); ?>%</span><?php endif; ?>
                        <?php if ($row['destacado']): ?><span class="badge badge-featured">Destacado</span><?php endif; ?>
                        <?php if ($row['recomendado']): ?><span class="badge badge-reco">Recomendado</span><?php endif; ?>
                        <span class="status <?php echo $availabilityClass; ?>"><?php echo $row['availability']; ?></span>
                    </div>
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <div class="product-meta">
                        <span class="chip muted"><?php echo $catLabel ?: 'Perfume'; ?></span>
                        <h3><?php echo $row['name']; ?></h3>
                        <?php $desc = $row['description'] ?? ''; ?>
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
            </div>
        </section>
    <?php endif; ?>
</main>

<section class="social-links">
    <h3>Síguenos en nuestras redes</h3>
    <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
    <a href="https://www.facebook.com/Paola S Ordonez" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
    <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i> Twitter</a>
    <a href="https://www.tiktok.com/@pao.1324" target="_blank"><i class="fab fa-tiktok"></i> TikTok</a>
</section>

<footer class="home-footer">
    <p>&copy; Aura Essence - El arte de la perfumería de lujo.</p>
</footer>

<a href="https://wa.me/3135086534?text=Hola! Me gustaría hacer una consulta general." class="whatsapp-float" target="_blank" aria-label="Escríbenos por WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<script>
    const container = document.querySelector('.carousel-container');
    const slides = Array.from(container.querySelectorAll('.carousel-slide'));
    const dots = Array.from(document.querySelectorAll('.dot'));
    const progressFill = document.querySelector('.progress-fill');
    let index = 0; let slideInterval;
    function showSlide(n){ index=(n+slides.length)%slides.length; container.style.transform=`translateX(-${index*100}%)`; dots.forEach((d,i)=>d.classList.toggle('active',i===index)); if(progressFill) progressFill.style.width=`${((index+1)/slides.length)*100}%`; }
    function startCarousel(){ slideInterval=setInterval(()=>showSlide(index+1),5000); }
    dots.forEach((dot,i)=>dot.addEventListener('click',()=>{ clearInterval(slideInterval); showSlide(i); startCarousel(); }));
    showSlide(0); startCarousel();
</script>
</body>
</html>
<?php 
// ============================================
// CERRAR CONEXIÓN
// ============================================
if (isset($stmt)) $stmt->close(); 
$conn->close(); 
?>
