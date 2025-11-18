# Solución de Problemas de Conexión

## Paso 1: Ejecutar el Script de Prueba

Abre en tu navegador:
```
http://localhost/Prueba_nivel_del_Logro/test_connection.php
```

Este script te dirá exactamente cuál es el problema.

## Problemas Comunes y Soluciones

### ❌ Error: "La base de datos no existe"

**Solución:**
1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Ve a la pestaña "Importar"
3. Selecciona el archivo `database/denuncias.sql`
4. Haz clic en "Continuar"
5. Verifica que la base de datos `denuncias_municipio` se haya creado

**O manualmente:**
1. Abre phpMyAdmin
2. Crea una nueva base de datos llamada `denuncias_municipio`
3. Selecciona esa base de datos
4. Ve a la pestaña "SQL"
5. Copia y pega el contenido de `database/denuncias.sql`
6. Ejecuta el script

### ❌ Error: "Access denied for user 'root'@'localhost'"

**Solución:**
1. Abre el archivo `config/database.php`
2. Verifica las credenciales:
   ```php
   private $username = "root";  // Tu usuario de MySQL
   private $password = "";      // Tu contraseña (vacía por defecto en XAMPP)
   ```
3. Si tienes contraseña en MySQL, cámbiala en el archivo

### ❌ Error: "MySQL no está corriendo"

**Solución:**
1. Abre el Panel de Control de XAMPP
2. Verifica que el botón de MySQL esté en verde (corriendo)
3. Si está en rojo, haz clic en "Start" junto a MySQL
4. Espera a que el estado cambie a verde

### ❌ Error: "PDO extension not loaded"

**Solución:**
1. Abre el archivo `php.ini` de XAMPP (generalmente en `C:\xampp\php\php.ini`)
2. Busca las líneas:
   ```ini
   ;extension=pdo_mysql
   ```
3. Quita el punto y coma (;) al inicio:
   ```ini
   extension=pdo_mysql
   ```
4. Guarda el archivo
5. Reinicia Apache en XAMPP

### ❌ Error: "Connection refused" o "Can't connect to MySQL server"

**Solución:**
1. Verifica que MySQL esté corriendo en XAMPP
2. Verifica el puerto de MySQL (por defecto es 3306)
3. Si usas un puerto diferente, modifica `config/database.php`:
   ```php
   private $host = "localhost:3307";  // Si tu puerto es 3307
   ```

## Verificación Rápida

Ejecuta estos pasos en orden:

1. ✅ ¿MySQL está corriendo en XAMPP? → Panel de Control XAMPP
2. ✅ ¿La base de datos existe? → phpMyAdmin → Ver lista de bases de datos
3. ✅ ¿Las credenciales son correctas? → Revisa `config/database.php`
4. ✅ ¿PDO está habilitado? → Revisa `php.ini`

## Probar la API Directamente

Abre en tu navegador:
```
http://localhost/Prueba_nivel_del_Logro/api/denuncias.php
```

Si hay un error de conexión, verás un JSON con el mensaje de error.

## Contacto

Si el problema persiste después de seguir estos pasos, verifica:
- Versión de PHP (debe ser 7.4 o superior)
- Versión de MySQL (debe ser 5.7 o superior)
- Logs de error de Apache (en XAMPP: `C:\xampp\apache\logs\error.log`)

---

**Desarrollado por:** Milenka Segundo Arteaga  
**Año:** 2025

