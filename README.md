# Perfucata - Catálogo de Perfumes

## Para Localhost
1. Inicia XAMPP (Apache + MySQL)
2. Ve a `http://localhost/perfucata/`

## Para InfinityFree
1. Sube todos los archivos
2. En tu servidor, crea `config_db.php` con tus credenciales:
```php
<?php
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_base_datos";
?>
```
3. ¡Listo!

## Secretos
- `config_db.php` está en `.gitignore` - **NO se sube a GitHub**
- Solo existe en tu servidor InfinityFree