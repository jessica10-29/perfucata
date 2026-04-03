<?php
header('Content-Type: text/html; charset=utf-8');
include 'db_connect.php';

// Asegurar columnas para descuentos y flags
$schemaUpdates = [
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS descuento DECIMAL(5,2) DEFAULT 0.00",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS destacado TINYINT(1) DEFAULT 0",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS recomendado TINYINT(1) DEFAULT 0"
];
foreach ($schemaUpdates as $sql) { $conn->query($sql); }

// Parámetros de búsqueda
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';
$doSearch = ($q !== '' || $category > 0 || $availability !== '');

// Nombres para autocompletar
$nameRes = $conn->query("SELECT nombre_titulo FROM perfumeria_total WHERE tipo='producto' ORDER BY nombre_titulo");
$allNames = [];
while ($n = $nameRes->fetch_assoc()) { $allNames[] = $n['nombre_titulo']; }

// Categorías y stats
$categories = [];
$categoryRes = $conn->query("SELECT id, nombre_titulo FROM perfumeria_total WHERE tipo='categoria' ORDER BY nombre_titulo");
while ($c = $categoryRes->fetch_assoc()) { $categories[] = $c; }
$catStats = [];
$statRes = $conn->query("SELECT relacion_id, COUNT(*) total, SUM(CASE WHEN descuento>0 THEN 1 ELSE 0 END) ofertas FROM perfumeria_total WHERE tipo='producto' GROUP BY relacion_id");
while ($s = $statRes->fetch_assoc()) { $catStats[$s['relacion_id']] = $s; }

// Búsqueda
$results = null;
if ($doSearch) {
    $filters = [];$types='';$params=[];
    $select = "id, nombre_titulo as name, precio as price, descuento, destacado, recomendado, clave_imagen as image, estado as availability, contenido_texto as description, relacion_id";
    $query = "SELECT $select FROM perfumeria_total WHERE tipo='producto'";
    if ($q !== '') { $filters[] = "(nombre_titulo LIKE ? OR contenido_texto LIKE ?)"; $types.='ss'; $like="%$q%"; $params[]=$like; $params[]=$like; }
    if ($category > 0) { $filters[] = "relacion_id = ?"; $types.='i'; $params[]=$category; }
    if ($availability !== '') { $filters[] = "estado = ?"; $types.='s'; $params[]=$availability; }
    if ($filters) { $query .= " AND " . implode(' AND ', $filters); }
    $query .= " ORDER BY destacado DESC, descuento DESC, fecha_registro DESC LIMIT 24";
    $stmt = $conn->prepare($query);
    if ($types) { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $results = $stmt->get_result();
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
<header>
    <h1>Aura Essence</h1>
    <nav>
        <a href="index.php" class="active">Inicio</a>
        <a href="products.php">Fragancias</a>
        <a href="blog.php">Blog</a>
        
    </nav>
</header>

<main>
    <section class="welcome hero">
        <div class="hero-text">
            <p class="eyebrow">Alta perfumería · Atención personalizada</p>
            <h2 class="highlight-gold">Elegancia en cada gota</h2>
            <p class="lead">En <strong>Aura Essence</strong> transformamos recuerdos en fragancias. Curamos aromas de autor y firmas globales para que tu presencia hable antes que las palabras.</p>
            <div class="hero-cta">
                <a class="btn-gold" href="#buscador">Buscar perfume</a>
            </div>
            <div class="stats-container">
                <div><h4 class="highlight-gold plain">+15 Años</h4><p>De experiencia</p></div>
                <div><h4 class="highlight-gold plain">100% Original</h4><p>Garantizado</p></div>
                <div><h4 class="highlight-gold plain">Premium</h4><p>Marcas globales</p></div>
            </div>
        </div>
        <div class="hero-badge">
            <span>Fragancias curadas</span>
            <strong><?php echo date('Y'); ?></strong>
        </div>
    </section>

    <section class="carousel">
        <div class="carousel-container">
            <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=1200" alt="Fragancia premium con notas florales">
            <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1200" alt="Lujo y estilo en tu tocador">
            <img src="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=1200" alt="Esencias naturales selectas">
            <img src="https://images.unsplash.com/photo-1512777576244-b846ac3d816f?auto=format&fit=crop&w=1200" alt="Colección real de perfumes">
            <img src="https://images.unsplash.com/photo-1583467875263-d50dec37a88c?auto=format&fit=crop&w=1200" alt="Aromas exclusivos de autor">
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

    <section class="panel-grid" id="buscador">
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

        <section class="search-panel glass-card">
            <div class="search-head">
                <div>
                    <p class="eyebrow">Encuentra tu aroma</p>
                    <h3>Buscador de perfumes</h3>
                    <p class="lead">Busca por nombre, filtra por categoría o disponibilidad. Mostramos solo los perfumes que coinciden.</p>
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

<footer>
    <p>&copy; <?php echo date("Y"); ?> Aura Essence - El arte de la perfumería de lujo.</p>
</footer>

<a href="https://wa.me/3135086534?text=Hola! Me gustaría hacer una consulta general." class="whatsapp-float" target="_blank" aria-label="Escríbenos por WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<script>
    const container = document.querySelector('.carousel-container');
    const slides = Array.from(container.querySelectorAll('img'));
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
<?php if (isset($stmt)) $stmt->close(); $conn->close(); ?>

