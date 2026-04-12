# 🚨 CONFIGURACIÓN OBLIGATORIA PARA INFINITYFREE

## ❌ ERROR ACTUAL
Estás viendo este error porque `config_db.php` no tiene las credenciales correctas.

## ✅ SOLUCIÓN RÁPIDA

### PASO 1: Obtén tus credenciales
1. Ve a tu **Panel de Control de InfinityFree**
2. Haz clic en **MySQL Databases**
3. Busca tu **contraseña** (está oculta, haz clic para mostrar)

### PASO 2: Configura config_db.php
**Opción A: Usar el archivo de ejemplo**
1. En **InfinityFree File Manager**, sube `config_db_ejemplo.php`
2. Renómbralo a `config_db.php`
3. Edítalo y reemplaza `"MI_CONTRASEÑA_REAL"` con tu contraseña real

**Opción B: Crear desde cero**
1. Crea un archivo `config_db.php` en `htdocs/`
2. Copia este contenido:
```php
<?php
$servername = "localhost";
$username = "if0_41640364"; // Tu usuario real
$password = "TU_CONTRASEÑA_REAL"; // Pon tu contraseña real
$dbname = "if0_41640364_perfucata"; // Tu BD real
?>
```

### PASO 3: Verifica
- Recarga tu sitio
- Si aún hay error, verifica que la contraseña sea correcta

## 🔍 ¿Dónde encontrar las credenciales?

**Panel InfinityFree → MySQL Databases → Database Details**

Deberías ver algo como:
- **Database Username**: `if0_41640364`
- **Database Password**: `********` (haz clic para ver)
- **Database Name**: `if0_41640364_perfucata`

## 📝 Archivo config_db.php actual:
```php
$username = "if0_41640364";
$password = "TU_CONTRASEÑA_REAL_AQUI"; // ← CAMBIA ESTO
$dbname = "if0_41640364_perfucata";
```

## 🆘 ¿Aún no funciona?
- Verifica que copiaste la contraseña correctamente
- Asegúrate de que la base de datos existe en InfinityFree
- Si cambiaste el nombre de usuario o BD, actualízalos

¡Una vez configurado, el sitio funcionará! 🎉