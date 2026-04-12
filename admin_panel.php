<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Asegurar columnas nuevas
$schemaUpdates = [
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS descuento DECIMAL(5,2) DEFAULT 0.00",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS destacado TINYINT(1) DEFAULT 0",
    "ALTER TABLE perfumeria_total ADD COLUMN IF NOT EXISTS recomendado TINYINT(1) DEFAULT 0"
];
foreach ($schemaUpdates as $sql) {
    $conn->query($sql);
}

// Borrar producto
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM perfumeria_total WHERE id = $id AND tipo = 'producto'");
    header("Location: admin_panel.php?msg=deleted");
    exit();
}

// Borrar post
if (isset($_GET['delete_post'])) {
    $id = intval($_GET['delete_post']);
    $conn->query("DELETE FROM perfumeria_total WHERE id = $id AND tipo = 'blog'");
    header("Location: admin_panel.php?msg=post_deleted");
    exit();
}

// Alta o actualización de producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']); // URL fallback
    $avail = $_POST['availability'];
    $desc = $_POST['description'];
    $category = intval($_POST['category']);
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $featured = isset($_POST['featured']) ? 1 : 0;
    $recommended = isset($_POST['recommended']) ? 1 : 0;

    // Manejo de subida de imagen desde archivo
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','webp'];
        $uploadDir = __DIR__ . '/uploads';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
        $original = $_FILES['image_file']['name'];
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_\\.-]/', '_', $original);
            $target = $uploadDir . '/' . $safeName;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target)) {
                $image = 'uploads/' . $safeName;
            }
        }
    }

    // Límites de descuento
    if ($discount < 0) $discount = 0;
    if ($discount > 90) $discount = 90;

    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $id = intval($_POST['product_id']);
        $stmt = $conn->prepare("UPDATE perfumeria_total SET nombre_titulo=?, precio=?, clave_imagen=?, estado=?, contenido_texto=?, relacion_id=?, descuento=?, destacado=?, recomendado=? WHERE id=? AND tipo='producto'");
        $stmt->bind_param("sdsssidiii", $name, $price, $image, $avail, $desc, $category, $discount, $featured, $recommended, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO perfumeria_total (tipo, nombre_titulo, precio, clave_imagen, estado, contenido_texto, relacion_id, descuento, destacado, recomendado) VALUES ('producto', ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsssidii", $name, $price, $image, $avail, $desc, $category, $discount, $featured, $recommended);
    }

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_panel.php?msg=success");
        exit();
    }
}

// Datos de edición
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM perfumeria_total WHERE id = $id AND tipo = 'producto'");
    if ($res && $res->num_rows > 0) {
        $edit_data = $res->fetch_assoc();
    }
}

// Crear post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_post'])) {
    $title = $_POST['title'];
    $image = $_POST['blog_image'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO perfumeria_total (tipo, nombre_titulo, clave_imagen, contenido_texto) VALUES ('blog', ?, ?, ?)");
    $stmt->bind_param("sss", $title, $image, $content);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_panel.php?msg=post_success");
        exit();
    }
}

// Categorías
$categories = $conn->query("SELECT id, nombre_titulo FROM perfumeria_total WHERE tipo='categoria' ORDER BY nombre_titulo");
$categoryMap = [];
while ($c = $categories->fetch_assoc()) {
    $categoryMap[$c['id']] = $c['nombre_titulo'];
}
// Requery for select (consumed)
$categories = $conn->query("SELECT id, nombre_titulo FROM perfumeria_total WHERE tipo='categoria' ORDER BY nombre_titulo");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
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
                <a href="index.php">Ver Sitio</a>
                <a href="products.php">Fragancias</a>
                <a href="blog.php">Blog</a>
                <a href="logout.php" style="color: var(--danger)">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <main class="admin-section">
        <section class="form-admin">
            <h2><?php echo $edit_data ? 'Editar Fragancia' : 'Agregar Nuevo Producto'; ?></h2>
            <form method="POST" action="admin_panel.php" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $edit_data ? $edit_data['id'] : ''; ?>">
                <div class="form-group"><input type="text" name="name" placeholder="Nombre del Perfume" value="<?php echo $edit_data ? htmlspecialchars($edit_data['nombre_titulo']) : ''; ?>" required></div>
                <div class="form-group"><input type="number" step="0.01" name="price" placeholder="Precio ($)" value="<?php echo $edit_data ? $edit_data['precio'] : ''; ?>" required></div>
                <div class="form-group"><input type="text" name="image" placeholder="URL de la Imagen (opcional)" value="<?php echo $edit_data ? htmlspecialchars($edit_data['clave_imagen']) : ''; ?>"></div>
                <div class="form-group">
                    <label style="color:#e2e8f0; font-size:0.9rem; display:block; margin-bottom:6px;">Subir imagen (archivo)</label>
                    <input type="file" name="image_file" accept="image/*">
                    <small>Desde computador o celular (jpg, png, webp).</small>
                </div>
                <div class="form-group">
                    <select name="category" style="width:100%; padding:10px; border-radius:8px; background:var(--glass); color:#fff; border:1px solid var(--glass-border);">
                        <option value="0">Sin categoría</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($edit_data && $edit_data['relacion_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo $cat['nombre_titulo']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="availability" style="width:100%; padding:10px; border-radius:8px; background:var(--glass); color:#fff; border:1px solid var(--glass-border);">
                        <option value="Disponible" <?php echo ($edit_data && $edit_data['estado'] == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                        <option value="Agotado" <?php echo ($edit_data && $edit_data['estado'] == 'Agotado') ? 'selected' : ''; ?>>Agotado</option>
                        <option value="En Espera" <?php echo ($edit_data && $edit_data['estado'] == 'En Espera') ? 'selected' : ''; ?>>En espera</option>
                    </select>
                </div>
                <div class="form-group"><input type="number" step="0.01" min="0" max="90" name="discount" placeholder="Descuento %" value="<?php echo $edit_data ? $edit_data['descuento'] : ''; ?>"> <small>Ofertas o descuentos</small></div>
                <div class="form-group flags">
                    <label><input type="checkbox" name="featured" <?php echo ($edit_data && $edit_data['destacado']) ? 'checked' : ''; ?>> Producto destacado</label>
                    <label><input type="checkbox" name="recommended" <?php echo ($edit_data && $edit_data['recomendado']) ? 'checked' : ''; ?>> Recomendación automática</label>
                </div>
                <div class="form-group">
                    <textarea name="description" placeholder="Descripción breve" style="width:100%; height:100px; background:var(--glass); border-radius:8px; border:1px solid var(--glass-border); color:#fff; padding:10px;"><?php echo $edit_data ? htmlspecialchars($edit_data['contenido_texto']) : ''; ?></textarea>
                </div>
                <button type="submit" name="add_product" class="btn-gold" style="width:100%; border:none;">
                    <?php echo $edit_data ? 'Actualizar Cambios' : 'Publicar en Catálogo'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="admin_panel.php" class="btn-gold" style="display:block; text-align:center; background:#444; color:#fff; margin-top:10px; text-decoration:none;">Cancelar Edición</a>
                <?php endif; ?>
            </form>
        </section>

        <section class="glass-card" style="padding:20px;">
            <h2>Gestión de Inventario</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Estado</th>
                        <th>Flags</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM perfumeria_total WHERE tipo = 'producto' ORDER BY fecha_registro DESC");
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='".$row['clave_imagen']."' style='width:50px; height:50px; border-radius:5px; object-fit:cover;'></td>";
                        echo "<td>".$row['nombre_titulo']."<br><small style='color:#94a3b8;'>".$row['contenido_texto']."</small></td>";
                        $catLabel = isset($categoryMap[$row['relacion_id']]) ? $categoryMap[$row['relacion_id']] : '-';
                        echo "<td>".$catLabel."</td>";
                        echo "<td>$".$row['precio']."</td>";
                        echo "<td>".($row['descuento'] > 0 ? '-'.$row['descuento'].'%' : '-')."</td>";
                        echo "<td>".$row['estado']."</td>";
                        $flags = [];
                        if ($row['destacado']) $flags[] = 'Destacado';
                        if ($row['recomendado']) $flags[] = 'Recomendado';
                        echo "<td>".(count($flags) ? implode(', ', $flags) : '-')."</td>";
                        echo "<td>
                                <a href='admin_panel.php?edit=".$row['id']."' class='btn-action btn-edit'>Editar</a>
                                <a href='admin_panel.php?delete=".$row['id']."' class='btn-action btn-delete' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="form-admin" style="margin-top: 50px;">
            <h2>Redactar Nuevo Artículo de Blog</h2>
            <form method="POST" action="admin_panel.php">
                <div class="form-group"><input type="text" name="title" placeholder="Título del Artículo" required></div>
                <div class="form-group"><input type="text" name="blog_image" placeholder="URL de la Imagen del Artículo"></div>
                <div class="form-group"><textarea name="content" placeholder="Contenido del artículo..." style="width:100%; height:150px; background:var(--glass); border-radius:8px; border:1px solid var(--glass-border); color:#fff; padding:10px;" required></textarea></div>
                <button type="submit" name="add_post" class="btn-gold" style="width:100%; border:none;">Publicar en Blog</button>
            </form>
        </section>

        <section class="glass-card" style="padding:20px;">
            <h2>Gestión de Publicaciones del Blog</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Título</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $blog_result = $conn->query("SELECT id, nombre_titulo as title, fecha_registro as date_posted FROM perfumeria_total WHERE tipo = 'blog' ORDER BY fecha_registro DESC");
                    while($blog_row = $blog_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$blog_row['date_posted']."</td>";
                        echo "<td>".$blog_row['title']."</td>";
                        echo "<td>
                                <a href='admin_panel.php?delete_post=".$blog_row['id']."' class='btn-action btn-delete' onclick='return confirm(\"¿Eliminar este artículo?\")'>Eliminar</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>




