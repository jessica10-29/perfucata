# 🚀 Guía para Sincronizar con InfinityFree

## Problema Resuelto
Tu código ahora detecta automáticamente si está en localhost (desarrollo) o en InfinityFree (producción).

## Pasos para Sincronizar

### 1. Subir archivos al servidor
- Sube TODOS los archivos de tu proyecto a InfinityFree (excepto `config_db.php` que se ignora)
- El `.gitignore` evita que subas archivos sensibles

### 2. Crear base de datos en InfinityFree
1. Ve a tu panel de InfinityFree → **MySQL Databases**
2. Crea una nueva base de datos
3. Importa tu archivo `database.sql`

### 3. Configurar credenciales en el servidor
1. En InfinityFree, crea un archivo llamado `config_db.php` en la raíz de tu sitio
2. Copia y pega este contenido, reemplazando con tus datos reales:

```php
<?php
// CONFIGURACIÓN DE BASE DE DATOS PARA INFINITYFREE
$servername = "localhost"; // Siempre localhost en InfinityFree
$username = "if0_41640364"; // Tu usuario real
$password = "TU_CONTRASEÑA_REAL"; // Tu contraseña real
$dbname = "if0_41640364_perfucata"; // Tu base de datos real
?>
```

### 4. Verificar funcionamiento
- Visita tu sitio en InfinityFree
- Si hay errores, aparecerán en pantalla (activé el debug)
- Si funciona, ¡listo!

## ¿No funciona?
Si aún tienes problemas:
1. Revisa que `config_db.php` existe en el servidor
2. Verifica que las credenciales sean correctas
3. Asegúrate de que la base de datos existe y tiene datos

## Credenciales de InfinityFree
Encuéntralas en:
- Panel de control → MySQL Databases
- O en el correo de confirmación de InfinityFree