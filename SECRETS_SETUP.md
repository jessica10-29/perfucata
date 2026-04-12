# 🔐 CONFIGURACIÓN DE VARIABLES DE ENTORNO (SECRETS)

## Para InfinityFree - Configuración de Secrets

### ¿Qué son las variables de entorno?
Son "secrets" que mantienen tus credenciales fuera del código. El código las lee automáticamente.

### Cómo configurar en InfinityFree:

#### PASO 1: Acceder al Panel de Control
1. Ve a tu **Panel de Control de InfinityFree**
2. Busca la sección **"Environment Variables"** o **"Variables de Entorno"**

#### PASO 2: Configurar las variables
Crea estas 4 variables de entorno:

```
DB_HOST = localhost
DB_USER = if0_41640364
DB_PASS = TU_CONTRASEÑA_REAL_DE_INFINITYFREE
DB_NAME = if0_41640364_perfucata
```

#### PASO 3: ¿Dónde conseguir los valores?
- **DB_HOST**: Siempre `localhost` en InfinityFree
- **DB_USER**: Tu usuario de BD (normalmente `if0_` + números)
- **DB_PASS**: Tu contraseña real de MySQL
- **DB_NAME**: Tu nombre de base de datos

**Encuéntralos en:** Panel → MySQL Databases → Database Details

#### PASO 4: Reiniciar
- Después de configurar las variables, **reinicia tu sitio**
- Ve a tu sitio web y debería funcionar

## 🔍 Verificación
Si configuraste correctamente, el sitio funcionará sin errores.

## 🆘 Problemas comunes
- **"Variable de entorno DB_USER no configurada"**: Falta configurar las variables
- **Error de conexión**: Verifica que la contraseña sea correcta
- **Base de datos no existe**: Asegúrate de crear la BD en InfinityFree

## 📝 Ventajas de usar Secrets
- ✅ Credenciales fuera del código
- ✅ Más seguro que archivos de configuración
- ✅ Fácil de cambiar sin tocar el código
- ✅ Compatible con deployments automáticos

¡Una vez configurado, sincroniza automáticamente! 🚀