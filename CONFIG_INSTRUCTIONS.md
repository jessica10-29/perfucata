# 🚨 CONFIGURACIÓN REQUERIDA PARA INFINITYFREE

## ⚠️ IMPORTANTE: Este archivo NO debe subirse a GitHub

Para que tu sitio funcione en InfinityFree, necesitas crear un archivo `config_db.php` con tus credenciales reales.

## 📋 Pasos para configurar:

### 1. Obtén tus credenciales de InfinityFree:
- Ve a tu **Panel de Control de InfinityFree**
- Haz clic en **MySQL Databases**
- Busca la sección **Database Details**
- Copia: Usuario, Contraseña y Nombre de la Base de Datos

### 2. Crea el archivo config_db.php:
En la raíz de tu sitio (mismo nivel que index.php), crea un archivo llamado `config_db.php` con este contenido:

```php
<?php
// CONFIGURACIÓN DE BASE DE DATOS PARA INFINITYFREE
$servername = "localhost"; // NO CAMBIES ESTO
$username = "TU_USUARIO_REAL_AQUI"; // Ejemplo: if0_41640364
$password = "TU_CONTRASEÑA_REAL_AQUI"; // Tu contraseña real
$dbname = "TU_BASE_DE_DATOS_REAL_AQUI"; // Ejemplo: if0_41640364_perfucata
?>
```

### 3. Reemplaza con tus datos reales:
- **Usuario**: Normalmente empieza con `if0_` seguido de números
- **Contraseña**: La que configuraste al crear la base de datos
- **Base de datos**: Normalmente `if0_XXXXXX_tu_nombre`

### 4. Verifica que funcione:
- Sube todos los archivos a InfinityFree
- Visita tu sitio
- Si hay errores, aparecerán en pantalla

## 🔍 Si aún no funciona:
1. Verifica que `config_db.php` existe en el servidor
2. Confirma que las credenciales son correctas
3. Asegúrate de que la base de datos existe y tiene datos
4. Revisa que `.htaccess` se haya subido correctamente

## 📞 ¿Necesitas ayuda?
Si me das tus credenciales de InfinityFree, puedo configurar el archivo por ti.