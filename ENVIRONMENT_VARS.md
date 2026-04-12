# 🔧 CONFIGURACIÓN DE VARIABLES DE ENTORNO POR PLATAFORMA

## InfinityFree

### Paso 1: Acceder al Panel
1. Ve a tu **Panel de Control de InfinityFree**
2. Busca **"Environment Variables"** o **"Variables de Entorno"**

### Paso 2: Crear Variables
Crea estas 4 variables:

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `DB_HOST` | `localhost` | Siempre localhost en InfinityFree |
| `DB_USER` | `if0_41640364` | Tu usuario de BD |
| `DB_PASS` | `tu_contraseña_real` | Tu contraseña real de MySQL |
| `DB_NAME` | `if0_41640364_perfucata` | Tu nombre de base de datos |

### Paso 3: Aplicar Cambios
- Guarda los cambios
- **Reinicia** tu sitio web
- ¡Listo! Se sincroniza automáticamente

## Otros Hosting (000webhost, Hostinger, etc.)

### cPanel
1. Ve a **cPanel** → **Variables de Entorno**
2. Agrega las mismas 4 variables

### DirectAdmin
1. Ve a **Panel de Control** → **Variables de Entorno**
2. Configura las variables

### Plesk
1. Ve a **Sitios Web** → **Configuración PHP**
2. Agrega las variables en **Variables de entorno**

## Verificación

Después de configurar, tu sitio debería funcionar sin errores de conexión.

## 🔍 ¿Dónde conseguir los valores?

**Panel de Hosting → MySQL Databases → Database Details**

Busca:
- Database Host/Server
- Database Username
- Database Password
- Database Name

## 📝 Ejemplo de configuración:

```bash
# Para Linux/Mac
export DB_HOST=localhost
export DB_USER=tu_usuario
export DB_PASS=tu_contraseña
export DB_NAME=tu_base_datos

# Para Windows (Command Prompt)
set DB_HOST=localhost
set DB_USER=tu_usuario
set DB_PASS=tu_contraseña
set DB_NAME=tu_base_datos
```

¡Las credenciales quedan fuera del código y se sincronizan automáticamente! 🚀