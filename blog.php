<?php
header('Content-Type: text/html; charset=utf-8');
include 'db_connect.php';

$posts = [];
$sql = "SELECT nombre_titulo as title, clave_imagen as image, contenido_texto as content, fecha_registro as date_posted FROM perfumeria_total WHERE tipo = 'blog' ORDER BY fecha_registro DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

$extraSecrets = [
    [
        'title' => 'No frotes la fragancia al aplicarla',
        'image' => 'images/perfumes/femeninos/chanel-no5.jpg',
        'content' => 'Cuando frotas el perfume sobre la piel rompes parte de sus notas de salida. Lo ideal es vaporizar y dejar que se asiente por si solo para conservar mejor el aroma real.',
        'date_posted' => date('Y-m-d')
    ],
    [
        'title' => 'Guarda tus perfumes lejos del calor y la luz',
        'image' => 'images/perfumes/masculinos/dior-sauvage.jpg',
        'content' => 'La luz directa, el vapor del baño y el calor aceleran el deterioro del perfume. Guardarlo en un lugar fresco y seco ayuda a conservar su aroma, color y duración.',
        'date_posted' => date('Y-m-d', strtotime('-1 day'))
    ],
    [
        'title' => 'Aprende la diferencia entre EDT, EDP y Parfum',
        'image' => 'images/perfumes/masculinos/acqua-di-gio.jpg',
        'content' => 'Eau de Toilette suele sentirse más ligera y fresca. Eau de Parfum tiene más concentración y duración. Parfum es más intenso, profundo y persistente sobre la piel.',
        'date_posted' => date('Y-m-d', strtotime('-2 days'))
    ],
    [
        'title' => 'Elige tu perfume según el momento del día',
        'image' => 'images/perfumes/infantil/mustela-musti.png',
        'content' => 'Para el día funcionan mejor aromas frescos, limpios o cítricos. Para la noche suelen lucir más los acordes dulces, amaderados, especiados o envolventes.',
        'date_posted' => date('Y-m-d', strtotime('-3 days'))
    ],
    [
        'title' => 'La hidratación mejora la duración del perfume',
        'image' => 'images/perfumes/femeninos/dior-jadore.jpg',
        'content' => 'Una piel hidratada retiene mejor la fragancia. Si aplicas crema neutra antes del perfume, notarás una fijación más uniforme y una evolución más agradable.',
        'date_posted' => date('Y-m-d', strtotime('-4 days'))
    ]
];

$posts = array_merge($posts, $extraSecrets);
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | Aura Essence</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <h1 class="highlight-gold">Aura Blog</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="products.php">Fragancias</a>
            <a href="blog.php" class="active">Blog</a>
            <a href="contact.php">Contacto</a>
        </nav>
    </header>

    <main class="catalog-page">
        <section class="section-head" style="padding:0 0 24px 0;">
            <div>
                <p class="eyebrow">Consejos y cultura olfativa</p>
                <h2>Secretos de perfumería</h2>
                <p class="lead" style="color:#cbd5e1; max-width:760px;">Descubre cómo aplicar, conservar y elegir mejor tus fragancias con consejos sencillos, elegantes y útiles para el día a día.</p>
            </div>
        </section>

        <section class="blog-container">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <article class="blog-post glass-card" style="margin-bottom: 28px; padding: 28px;">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width:100%; height:300px; object-fit:cover; border-radius:15px; margin-bottom:20px;">
                        <?php endif; ?>
                        <h3 style="font-size: 1.8rem; margin-bottom: 14px;"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p style="color: #d6d3d1; font-size: 1.05rem; margin-bottom: 18px;"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <div style="border-top: 1px solid var(--glass-border); padding-top: 14px; color: var(--gold); font-size: 0.85rem;">
                            <i class="far fa-calendar-alt"></i> Publicado el <?php echo date('d/m/Y', strtotime($post['date_posted'])); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center; color:#e5e7eb;">Pronto compartiremos más secretos de perfumería contigo.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aura Essence - El arte de la perfumería de lujo.</p>
    </footer>

    <a href="https://wa.me/3135086534?text=Hola! Leí su blog y tengo una consulta." class="whatsapp-float" target="_blank" aria-label="Consulta por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</body>
</html>
